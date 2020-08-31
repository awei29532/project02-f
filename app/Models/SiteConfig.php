<?php

namespace App\Models;

/**
 * @property int $site_id
 * @property string $name
 * @property string $value
 * @property string $info
 * @property string $updated_at
 * @property string created_at
 */
class SiteConfig extends BaseModel
{
    const site_menu = 'site_menu';

    protected $table = 'site_config';

    protected $casts = [
        'site_id' => 'integer',
        'name' => 'string',
        'value' => 'string',
        'info' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
