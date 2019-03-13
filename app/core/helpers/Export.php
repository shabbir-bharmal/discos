<?php

class Export
{
    protected $collection;
    protected $output;
    private $buffer = '';

    function __construct($filtered_collection, OutputInterface $output)
    {
        $this->collection = $filtered_collection;
        $this->output = $output;
    }

    public function run($fields = 'all', $mark_as_done = true, $only_fresh_records = true)
    {
        $this->filter($only_fresh_records);

        #$cols = ($fields != 'all' && !empty($fields)) ? $fields : array_keys($this->collection->first()->getAttributes());
        if ($fields == 'all' || empty($fields)) {
            $cols = self::getAllModelAttributes();
        } else {
            $cols = $fields;
        }

        $this->makeHeadings($cols);

        $this->makeRows($cols);

        $this->markAsExported($mark_as_done);

        return $this->output->write(rtrim($this->buffer, "\n"), 'export-' . date('d-m-y') . '.csv');
    }

    private function makeHeadings($cols)
    {
        $buffer = array();

        foreach ($cols as $model => $model_cols) {
            foreach ($model_cols as $col) {
                $buffer[] = "$model $col";
            }
        }

        if (!empty($buffer))
            $this->buffer .= '"' . implode('","', $buffer) . '"' .  "\n";
    }

    private function makeRows($cols)
    {
        foreach ($this->collection as $row) {

            $line = '';

            foreach ($cols as $model => $model_cols) {
                foreach ($model_cols as $col) {
                    /*print_r($model);
                    print_r($model_cols);
                    print_r($row->$model);
                    //print_r($row); 
                    die;*/
                    if($row != null){
                        if($model == 'booking' || $row->$model != null){

                            $line[] = ($model == 'booking') ? (isset($row->$col)?$row->$col:""): ((isset($row->$model) && isset($row->$model->$col))?$row->$model->$col:"");
                        }
                    }
                }
            }

            if (!empty($line))
                $this->buffer .= '"' . implode('","', $line) . '"' . "\n";
        }
    }

    private function markAsExported($mark_as_done)
    {
        if (!$mark_as_done)
            return;

        $timestamp = date('Y-m-d H:i:s');

        // hoping for a nicer way that this... 
        foreach ($this->collection as $record) {
            $record->exported = $timestamp;
            $record->save();
        }
    }

    private function filter($only_fresh_records)
    {
        if ($only_fresh_records) {
            $this->collection = $this->collection->filter(function ($record) {
                        return $record->exported == null;
                    });
        }
    }

    public static function getAllModelAttributes()
    {
        $cols = array();
        $cols['client'] = array_keys(Client::all()->first()->getAttributes());
        $cols['booking'] = array_keys(Booking::all()->first()->getAttributes());
        $cols['package'] = array_keys(Package::all()->first()->getAttributes());
        return $cols;
    }

    public static function cleanPostFieldsForExport($postArray)
    {
        $cleaned = array();
        if (isset($postArray['client']))
            $cleaned['client'] = array_keys($postArray['client']);
        if (isset($postArray['booking']))
            $cleaned['booking'] = array_keys($postArray['booking']);
        if (isset($postArray['package']))
            $cleaned['package'] = array_keys($postArray['package']);
        return $cleaned;
    }

    public static function getCollectionFromPostFiltersForExport($postArray)
    {
        $filtered_collection = Booking::where('deleted', '=', 0)->get()->filter(function($booking) use ($postArray) {

                    foreach ($postArray as $model => $fields) {

                        foreach ($fields as $field => $filters) {

                            foreach ($filters as $compare => $value) {

                                if ($value == '')
                                    continue;

                                $booking_model = $model == 'booking' ? $booking : $booking->$model;

                                switch ($compare) {
                                    case '=':
                                        // allow for , and only 1 - (otherwise it might be a date)
                                        if (strpos($value, ",") !== false || strpos($value, "-") !== false && substr_count($value, '-') < 2) {
                                            $value_array = Helpers\StringsHelper::extractIntegers($value);
                                            if (!in_array($booking_model->$field, $value_array))
                                                return false;
                                        } else {
                                            if ($booking_model->$field != $value)
                                                return false;
                                        }

                                        break;
                                    case '>':
                                        $date_to_compare = DateTime::createFromFormat('d-m-Y', $value)->setTime(0, 0, 0);
                                        $date_value = DateTime::createFromFormat('d-m-Y', $booking_model->$field)->setTime(0, 0, 0);
                                        if ($date_value <= $date_to_compare)
                                            return false;
                                        break;
                                    case '<':
                                        $date_to_compare = DateTime::createFromFormat('d-m-Y', $value)->setTime(0, 0, 0);
                                        $date_value = DateTime::createFromFormat('d-m-Y', $booking_model->$field)->setTime(0, 0, 0);
                                        if ($date_value >= $date_to_compare)
                                            return false;
                                        break;
                                }
                            }
                        }
                    }

                    return true;
                });

        return $filtered_collection;
    }

}

?>
