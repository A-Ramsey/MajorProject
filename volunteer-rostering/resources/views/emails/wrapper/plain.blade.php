@php
use App\Models\Company;
$company = Company::firstOrCreate([
    'id' => 1
]);
@endphp
Dear @yield('name')


@yield('content')

Thanks

{{ $company->name ?? 'Volunteer Rostering' }}
