<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user information by IP with proper error handling
     *
     * @return array|null
     */
    public function getUserInformation()
    {
        try {
            // Check if IP is set and valid
            if (empty($this->ip)) {
                Log::warning('Administrator getUserInformation: No IP address provided');
                return $this->getDefaultGeoData();
            }

            // Validate IP format
            if (!filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                Log::warning("Administrator getUserInformation: Invalid IP address: {$this->ip}");
                return $this->getDefaultGeoData();
            }

            // Create proper context with timeout and headers
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'Mozilla/5.0 (compatible; Laravel App)',
                    'method' => 'GET',
                ]
            ]);

            $url = "http://www.geoplugin.net/php.gp?ip=" . urlencode($this->ip);
            $data = @file_get_contents($url, false, $context);

            if ($data === false) {
                Log::warning("Failed to fetch geo data for IP: {$this->ip}");
                return $this->getDefaultGeoData();
            }

            $result = @unserialize($data);
            
            if ($result === false || !is_array($result)) {
                Log::warning("Failed to unserialize geo data for IP: {$this->ip}");
                return $this->getDefaultGeoData();
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Error in getUserInformation: " . $e->getMessage());
            return $this->getDefaultGeoData();
        }
    }

    /**
     * Get default geo information when service fails
     *
     * @return array
     */
    private function getDefaultGeoData()
    {
        return [
            'geoplugin_request' => $this->ip ?? 'unknown',
            'geoplugin_status' => 206,
            'geoplugin_countryName' => 'Unknown',
            'geoplugin_countryCode' => 'XX',
            'geoplugin_city' => 'Unknown',
            'geoplugin_region' => 'Unknown',
            'geoplugin_latitude' => '0',
            'geoplugin_longitude' => '0',
            'geoplugin_timezone' => 'UTC'
        ];
    }
}