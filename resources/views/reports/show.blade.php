<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-6">Detail Laporan {{ ucfirst($reportType) }}</h1>
        <a href="{{ route('reports.download', $reportType) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-6 inline-block">Download Laporan</a>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    @foreach($data->first()->toArray() as $key => $value)
                        <th class="py-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    @foreach($item->toArray() as $value)
                        <td class="py-2">{{ $value }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
