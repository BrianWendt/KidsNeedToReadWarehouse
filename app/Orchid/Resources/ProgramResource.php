<?php

namespace App\Orchid\Resources;

use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class ProgramResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Program::class;

    /**
     * Get the descriptions for the screen.
     */
    public static function description(): ?string
    {
        return 'Programs fulfillments are for.';
    }

    /**
     * Get the displayable icon of the resource.
     */
    public static function icon(): string
    {
        return 'arrow-down-right-square';
    }

    /**
     * Get the resource should be displayed in the navigation
     */
    public static function displayInNavigation(): bool
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(): array
    {
        return [
            Input::make('name')
                ->title('Name')
                ->placeholder('Name of the source')
                ->maxlength(128)
                ->required(),

            TextArea::make('note')
                ->title('Notes')
                ->maxlength(600),

            CheckBox::make('starred')
                ->title('Favorited')
                ->sendTrueOrFalse()
                ->placeholder('Favorited'),
        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('display', 'Name')
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at->format('Y-m-d H:i A');
                }),
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id'),

            Sight::make('name'),

            Sight::make('starred', 'Favorited')
                ->render(function (Model $model) {
                    return $model->starred ? '★ Yes' : 'No';
                }),

            Sight::make('note', 'Notes'),
        ];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(): array
    {
        return [
            new \Orchid\Crud\Filters\DefaultSorted('name', 'asc'),
        ];
    }

    /**
     * Get relationships that should be eager loaded when performing an index query.
     */
    public function with(): array
    {
        return [];
    }
}
