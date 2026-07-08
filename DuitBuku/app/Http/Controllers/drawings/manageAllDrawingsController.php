<?php

namespace App\Http\Controllers\drawings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageAllDrawingsController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $type  = $request->input('type', '');

        $params = ['month' => $month];
        if ($type) $params['type'] = $type;

        $response = $this->callApi('GET', 'Drawing/GET_DrawingList', $params);
        $drawings = array_map(fn($d) => (object) $d, $response['Data'] ?? []);

        $total = array_sum(array_map(fn($d) => $d->amount, $drawings));
        $count = count($drawings);

        // This-month total: if filter is already current month use same list, else re-fetch
        $currentMonth = date('Y-m');
        if ($month === $currentMonth && ! $type) {
            $thisMonth = $total;
        } else {
            $tmResponse = $this->callApi('GET', 'Drawing/GET_DrawingList', ['month' => $currentMonth]);
            $tmDrawings = array_map(fn($d) => (object) $d, $tmResponse['Data'] ?? []);
            $thisMonth  = array_sum(array_map(fn($d) => $d->amount, $tmDrawings));
        }

        $summary = [
            'total'      => $total,
            'this_month' => $thisMonth,
            'count'      => $count,
        ];

        return view('drawings.all_drawings', compact('drawings', 'summary', 'month', 'type'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'Drawing/POST_Drawing_SaveUpdateDelete', [
            'action'      => 'save',
            'user_id'     => 1,
            'drawn_at'    => $request->input('drawn_at'),
            'type'        => $request->input('type'),
            'description' => $request->input('description'),
            'amount'      => $request->input('amount'),
            'notes'       => $request->input('notes'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'Drawing/POST_Drawing_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}
