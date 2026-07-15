<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Event::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Kategori',
            'Lokasi',
            'Tanggal Waktu',
            'Status',
        ];
    }

    public function map($event): array
    {
        return [
            $event->id,
            $event->judul,
            $event->kategori->nama ?? '-',
            $event->lokasi,
            $event->tanggal_waktu->format('Y-m-d H:i'),
            $event->status,
        ];
    }
}
