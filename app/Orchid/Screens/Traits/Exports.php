<?php

namespace App\Orchid\Screens\Traits;

use Orchid\Screen\{
    Actions\Link,
    Repository
};

use App\Custom\LengthAwareExportablePaginator;

trait Exports
{

    private static $export_parameter = '_export';

    public static function exportAction(string $format = 'csv', string $label = 'Export')
    {
        $href = '?' . http_build_query(self::exportActionQuery($format)) . '&ts=' . time();
        return Link::make($label)
            ->icon('download')
            ->href($href);
    }

    private static function exportActionQuery($format)
    {
        return array_merge(request()->query(), [self::$export_parameter => $format]);
    }

    /**
     * Override the view method to handle export requests
     * 
     * @throws \Throwable
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(array|Repository $httpQueryArguments = [])
    {
        if (empty($this->export_target)) {
            throw new \Exception('export_target property not set on ' . get_class($this));
        }
        if (empty($this->export_columns)) {
            throw new \Exception('export_columns property not set on ' . get_class($this));
        }
        if (request()->query(self::$export_parameter, 'html') !== 'html') {
            $repository = is_a($httpQueryArguments, Repository::class)
                ? $httpQueryArguments
                : $this->buildQueryRepository($httpQueryArguments);
            return $this->export($repository);
        }
        return parent::view($httpQueryArguments);
    }

    /**
     *
     * @param Repository $repository
     * @return void
     */
    public function export(Repository $repository)
    {
        $format = request()->query(self::$export_parameter, 'html');

        $results = $repository->getContent($this->export_target);

        if ($results instanceof LengthAwareExportablePaginator) {
            $data = [
                'rows' => $results->builder()->limit(100000)->get(),
                'columns' => $this->export_columns,
            ];
        } elseif (is_iterable($results)) {
            $data = [
                'rows' => $results,
                'columns' => $this->export_columns,
            ];
        } else {
            throw new \Exception('Model must use the Exports trait');
        }

        $filename = method_exists($this, 'exportFilename') ? $this->exportFilename() : $this->export_target;

        return $this->exportInstance()
            ->setFilename($filename)
            ->setFormat($format)
            ->setData($data)
            ->response();
    }

    public function exportInstance() : \App\Exports\Exports
    {
        return new \App\Exports\ExportsSimple();
    }
}
