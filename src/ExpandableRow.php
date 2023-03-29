<?php

namespace SPRIGS\ExpandableRow;

use Laravel\Nova\Fields\Field;

class ExpandableRow extends Field implements \Laravel\Nova\Fields\Unfillable
{
    public $textAlign = 'center';
    /**
     * ExpandableRow constructor.
     *
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     */
    public function __construct($name = '', $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->onlyOnIndex();
        
        return $this->withMeta([
            'toggleLabel' => 'Details',
        ]);
    }

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'expandable-row';

    /**
     * Adapt the array to match the build itn nova preview data structure.
     *
     * @var array
     */
    private function structureDataForPreview($dataArray)
    {
        $dataArray = array_map(function ($item) {

            // if item value is string, convert to array of
            if (is_string($item['value'])) {
                $item['value'] = [$item['value']];
            }

            $item['value'] = array_map(function ($value) {
                return [
                    'display' => $value,
                ];
            }, $item['value']);

            return [
                'name' => $item['name'],
                'value' => $item['value'],
            ];
        }, $dataArray);

        return $dataArray;
    }

    /**
     * The field's resource details.
     *
     * @var array
     */
    public function expandingData(array $dataArray)
    {
        return $this->withMeta([
            'expandingData' => $this->structureDataForPreview($dataArray),
        ]);
    }

    /**
     * Move the field to the actions column.
     *
     * @var bool
     */
    public function moveToActions($shouldMoveToActions = true)
    {
        return $this->withMeta([
            'moveToActions' => $shouldMoveToActions
        ]);
    }


    /**
     * Move the field to the actions column.
     *
     * @var string
     */
    public function toggleLabel($toggleLabel = 'Details')
    {
        return $this->withMeta([
            'toggleLabel' => $toggleLabel
        ]);
    }
}
