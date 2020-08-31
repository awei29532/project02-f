<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property string $name
 * @property double $add_amount
 * @property int $bet_multiple
 * @property double $bet_required_amount
 * @property int $bet_valid_day
 * @property int $status
 * @property string $image
 * @property string $content
 * @property string $start_at
 * @property string $end_at
 * @property string $updated_at
 * @property string $created_at
 */
class Promotion extends BaseModel
{
    protected $table = 'promotion';
}
