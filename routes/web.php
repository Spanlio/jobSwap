<?php

use App\Http\Controllers\EmployerApprovalController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MessagesController;
use App\Livewire\Admin\ActionLog;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PostLogList;
use App\Livewire\Admin\Posts as AdminPosts;
use App\Livewire\Admin\Swaps as AdminSwaps;
use App\Livewire\Admin\Users as AdminUsers;
use App\Livewire\Chat\ConversationList;
use App\Livewire\Dashboard;
use App\Livewire\Payments\PaymentMethodForm;
use App\Livewire\Posts\Feed;
use App\Livewire\Posts\MyPosts;
use App\Livewire\Posts\PostForm;
use App\Livewire\Posts\Show as PostShow;
use App\Livewire\Swaps\MySwaps;
use Illuminate\Support\Facades\Route;

Route::get('/', Feed::class)->name('home');

Route::post('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('posts/create', PostForm::class)->name('posts.create');
    Route::get('posts/{post}/edit', PostForm::class)->name('posts.edit');
    Route::get('my/posts', MyPosts::class)->name('posts.mine');

    Route::get('my/messages', ConversationList::class)->name('messages.index');
    Route::get('messages/{conversation}', [MessagesController::class, 'show'])->name('messages.show');

    Route::get('my/swaps', MySwaps::class)->name('swaps.mine');

    Route::get('payment-method', PaymentMethodForm::class)->name('payment-method.edit');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::get('users', AdminUsers::class)->name('users');
        Route::get('posts', AdminPosts::class)->name('posts');
        Route::get('swaps', AdminSwaps::class)->name('swaps');
        Route::get('logs/actions', ActionLog::class)->name('logs.actions');
        Route::get('logs/posts', PostLogList::class)->name('logs.posts');
    });
});

Route::get('employer/respond/{token}', [EmployerApprovalController::class, 'show'])->name('employer.respond');
Route::post('employer/respond/{token}', [EmployerApprovalController::class, 'respond'])->name('employer.respond.submit');

Route::view('profile', 'profile')->middleware('auth')->name('profile');

// Public post detail - browsing is anonymous, chat/swap actions require auth (enforced in-component)
Route::get('posts/{post}', PostShow::class)->name('posts.show');

require __DIR__.'/auth.php';
