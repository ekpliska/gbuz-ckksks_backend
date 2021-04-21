<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\MeasuringInstrument as BaseMeasuringInstrument;

class MeasuringInstrument extends Model
{

    public $id;
    public $name;
    public $eqp_function_id;
    public $type;
    public $factory_number;
    public $commissioning_year;
    public $inventory_number;
    public $measuring_range;
    public $accuracy_class;
    public $verification_certificate;
    public $validity_date_from;
    public $validity_date_to;
    public $annually;
    public $status_verification;
    public $manufacturer;
    public $country;
    public $year_issue;
    public $type_own_id;
    public $placement_id;
    public $note;

}
