<?php
namespace ViewManager;

use Illuminate\View\Factory;

use CoreDispatcher\CoreDispatcher;

class TempViewFactory extends Factory
{
    public function make($view, $data = [], $mergeData = [])
    {
        CoreDispatcher::process();

        return parent::make($view, $data, $mergeData);
    }
}
