<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function delete(Admin $admin, Product $product)
    {
        // Contoh: Hanya admin yang dapat menghapus produk
        return $admin->isAdmin(); // Metode isAdmin() harus ada pada model User
    }
}
