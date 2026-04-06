<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\SeafoodItem;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeletedRecordController extends Controller
{
    public function index(Request $request): View
    {
        $selectedType = $request->string('type')->toString();

        $resources = collect($this->resources())
            ->map(function (array $resource, string $type) use ($selectedType) {
                if ($selectedType !== '' && $selectedType !== $type) {
                    $resource['records'] = collect();

                    return $resource;
                }

                $resource['records'] = $resource['model']::onlyTrashed()
                    ->with(['creator', 'updater', 'deleter'])
                    ->latest('deleted_at')
                    ->get();

                return $resource;
            });

        return view('admin.deleted-records.index', [
            'selectedType' => $selectedType,
            'resources' => $resources,
        ]);
    }

    public function restore(string $type, int $recordId): RedirectResponse
    {
        $resource = $this->resources()[$type] ?? null;

        abort_if(! $resource, 404, 'Tipe data tidak ditemukan.');

        $model = $resource['model']::onlyTrashed()->findOrFail($recordId);
        $model->restore();

        return redirect()->route('admin.deleted-records.index', ['type' => $type])->with('status', 'Data berhasil direstore.');
    }

    private function resources(): array
    {
        return [
            'users' => ['label' => 'Users', 'model' => User::class],
            'categories' => ['label' => 'Kategori', 'model' => Category::class],
            'seafood_items' => ['label' => 'Menu Seafood', 'model' => SeafoodItem::class],
            'promotions' => ['label' => 'Promo', 'model' => Promotion::class],
            'testimonials' => ['label' => 'Testimoni', 'model' => Testimonial::class],
            'orders' => ['label' => 'Pesanan', 'model' => Order::class],
            'company_profiles' => ['label' => 'Website Settings', 'model' => CompanyProfile::class],
        ];
    }
}
