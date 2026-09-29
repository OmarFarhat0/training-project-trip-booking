<?php

namespace App\Http\Requests;

use App\Models\Trip;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_city_id' => ['required', 'integer', 'exists:cities,id', 'different:to_city_id'],
            'to_city_id' => ['required', 'integer', 'exists:cities,id'],
            'bus_id' => ['required', 'integer', 'exists:buses,id'],
            'departure_time' => ['required', 'date', 'after:now'],
            'seat_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['bus_id', 'departure_time'])) {
                    return;
                }

                $busBusy = Trip::query()
                    ->where('bus_id', $this->integer('bus_id'))
                    ->where('departure_time', $this->input('departure_time'))
                    ->exists();

                if ($busBusy) {
                    $validator->errors()->add(
                        'bus_id',
                        'This bus is already scheduled at the given departure time.',
                    );
                }
            },
        ];
    }
}
