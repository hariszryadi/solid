<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
        /**
     * Folder views
     */
    protected $_view = 'report.';

    /**
     * Route index
     */
    protected $_route = 'report.index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = 'Report';
        $this->months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }

    /**
     * Display form filter for report.
     */
    public function daily()
    {
        $organizations = Organization::orderBy('id')->get();
        $years = Transaction::selectRaw('YEAR(created_at) as year')->distinct()->get();
        return view('report.daily', ['title' => $this->title, 'organizations' => $organizations, 'months' => $this->months, 'years' => $years]);
    }

    /**
     * Display a chart of the resource.
     */
    public function daily_result(Request $request)
    {
        $this->validate($request, [
            'organization' => 'required',
            'date' => 'required'
        ], [
            'organization.required' => 'Instansi harus diisi',
            'date.required' => 'Tanggal harus diisi'
        ]);

        $categoryArr = [];
        $seriesArr = [];

        $categories = Category::orderBy('id')->get();
        foreach ($categories as $key => $category) {
            $transaction = Transaction::where('category_id', $category->id)->where('organization_id', $request->organization)->whereDate('created_at', Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'))->sum('weight');
            
            array_push($categoryArr, $category->name);
            array_push($seriesArr, floatval($transaction));
        }

        return view('report.daily_result', ['title' => $this->title, 'categories' => $categoryArr, 'series' => $seriesArr]);
    }

    /**
     * Display form filter for report.
     */
    public function monthly()
    {
        $organizations = Organization::orderBy('id')->get();
        $years = Transaction::selectRaw('YEAR(created_at) as year')->distinct()->get();
        return view('report.monthly', ['title' => $this->title, 'organizations' => $organizations, 'months' => $this->months, 'years' => $years]);
    }

    /**
     * Display a chart of the resource.
     */
    public function monthly_result(Request $request)
    {
        $this->validate($request, [
            'organization' => 'required',
            'month' => 'required',
            'year' => 'required',
        ], [
            'organization.required' => 'Instansi harus diisi',
            'month.required' => 'Bulan harus diisi',
            'year.required' => 'Tahun harus diisi'
        ]);

        $startDate = Carbon::create($request->year, $request->month, 1, 0, 0, 0);
        $endDate = $startDate->copy()->endOfMonth();
        $currentDate = $startDate->copy();
        $dates = [];
        $organic = [];
        $anorganic = [];
        $b3 = [];

        while ($currentDate->lte($endDate)) {
            $sumOrganic = Transaction::where('category_id', 1)->where('organization_id', $request->organization)->whereDate('created_at', $currentDate->format('Y-m-d'))->sum('weight');
            $sumAnorganic = Transaction::where('category_id', 2)->where('organization_id', $request->organization)->whereDate('created_at', $currentDate->format('Y-m-d'))->sum('weight');
            $sumB3 = Transaction::where('category_id', 3)->where('organization_id', $request->organization)->whereDate('created_at', $currentDate->format('Y-m-d'))->sum('weight');
            $dates[] = $currentDate->format('d M');
            $organic[] = $sumOrganic;
            $anorganic[] = $sumAnorganic;
            $b3[] = $sumB3;

            $currentDate->addDay();
        }

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('d M');
            $organic[] = 0;
            $anorganic[] = 0;
            $b3[] = 0;

            $currentDate->addDay();
        }

        return view('report.monthly_result', ['title' => $this->title, 'dates' => $dates, 'organic' => $organic, 'anorganic' => $anorganic, 'b3' => $b3]);
    }
}
