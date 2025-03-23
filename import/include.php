<?php

define('DATETIME', date('Y-m-d H:i:s'));

function csv_to_array($filename = '', $delimiter = ',')
{
    $filename = 'files/'.$filename;
    if (! file_exists($filename) || ! is_readable($filename)) {
        return false;
    }

    $header = null;
    $data = [];
    if (($handle = fopen($filename, 'r')) !== false) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (! $header) {
                $header = $row;
            } else {
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }

    return $data;
}

function array_to_csv($filename, $data)
{
    $filename = 'files/'.$filename;
    if (file_exists($filename)) {
        unlink($filename);
    }
    $fp = fopen($filename, 'w');
    foreach ($data as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);
}

function dd($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit();
}

class Model
{
    public $map = [];

    public $properties = [];

    public function __construct($data = [])
    {
        if (! $data) {
            return;
        }
        foreach ($this->map as $key => $property) {
            if (! array_key_exists($property, $this->properties)) {
                echo "Property $property not found in properties\n";
                dd($data);
            } elseif (! array_key_exists($key, $data)) {
                echo "Key $key not found in map\n";
                dd($data);
            } else {
                $this->properties[$property] = trim($data[$key]);
            }
        }
    }

    public function valuesSql()
    {
        $values = [];
        foreach ($this->properties as $property => $value) {
            if ($value === null) {
                $values[] = 'NULL';
            } else {
                $values[] = '"'.addslashes($value).'"';
            }
        }

        return '('.implode(', ', $values).')';
    }
}

/**
 * @property string $table
 * @property array $columns
 * @property Model[] $models
 */
class Insert
{
    public $table;

    public $columns = [];

    public $models = [];

    public function __construct(string $table, string $model, array $data)
    {
        $this->table = $table;
        foreach ((new $model(false))->properties as $property => $value) {
            $this->columns[] = '`'.$property.'`';
        }
        foreach ($data as $row) {
            $Model = new $model($row);
            $id = $Model->properties['id'] ?? false;
            if ($id) {
                if (false && isset($this->models[$id])) {
                    echo "ID already set\n";
                    print_r($this->models[$id]);
                    dd($row);
                }
                $this->models[$id] = new $model($row);
            } else {
                $this->models[] = new $model($row);
            }
        }
    }

    public function sql()
    {
        $batch_size = 5000;
        $sql = '';
        foreach (array_chunk($this->models, $batch_size) as $models) {
            $sql .= $this->_sql($models);
        }

        return $sql;
    }

    /**
     * @param  Model[]  $models
     * @return string
     */
    private function _sql($models)
    {
        $sql = "INSERT INTO `$this->table` (";
        $sql .= implode(', ', $this->columns);
        $sql .= ") VALUES \n";
        $values = [];
        foreach ($models as $model) {
            $values[] = $model->valuesSql();
        }
        $sql .= implode(",\n", $values).";\n\n";

        return $sql;
    }
}

function extract_ein(string $string): string
{
    preg_match('/\d{2}-\d{7}/', $string, $matches);
    if ($matches) {
        return $matches[0];
    }

    return '';
}

$states = [
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming',
];
