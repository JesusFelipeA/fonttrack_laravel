<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Lugar;

class ReporteFalla extends Mailable
{
    use Queueable, SerializesModels;

    public $falla;
    public $pdfContent;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($falla, $pdfContent)
    {
        $this->falla = $falla;
        $this->pdfContent = $pdfContent;
        
        // Preparar los datos para la vista del email (igual que en el controlador)
        $lugar = Lugar::find($falla->id_lugar);
        $nombreLugar = $lugar ? $lugar->nombre : 'No especificado';

        $materials = [];
        if ($falla->materials) {
            $materials = is_string($falla->materials) ? json_decode($falla->materials, true) : $falla->materials;
            $materials = is_array($materials) ? $materials : [];
        }

        // ✅ IMPORTANTE: Agregar el id_reporte que faltaba en la vista
        $this->data = [
            'id_reporte' => $falla->id,  // ← ESTO FALTABA
            'lugar' => $nombreLugar,
            'eco' => $falla->eco ?? 'N/A',
            'placas' => $falla->placas ?? 'N/A',
            'marca' => $falla->marca ?? 'N/A',
            'anio' => $falla->anio ?? 'N/A',
            'km' => $falla->km ?? 'N/A',
            'fecha' => $falla->fecha ?? 'N/A',
            'nombre_conductor' => $falla->nombre_conductor ?? 'N/A',
            'descripcion' => $falla->descripcion ?? 'N/A',
            'observaciones' => $falla->observaciones ?? 'N/A',
            'autorizado_por' => $falla->autorizado_por ?? 'N/A',
            'reviso_por' => $falla->reviso_por ?? $falla->autorizado_por ?? 'N/A',
            'materials' => $materials,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reporte de Falla #' . $this->falla->id . ' - FontTrack')
                   ->view('emails.reporte_falla')  // ← Tu vista
                   ->with([
                       'data' => $this->data,      // ← Pasar los datos a la vista
                       'falla' => $this->falla
                   ])
                   ->attachData($this->pdfContent, 'reporte_falla_' . $this->falla->id . '.pdf', [
                       'mime' => 'application/pdf',
                   ]);
    }
}