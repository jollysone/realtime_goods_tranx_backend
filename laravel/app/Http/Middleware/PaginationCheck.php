<?php

namespace App\Http\Middleware;

use function App\failure;
use App\Helpers\CacheKey;
use App\Helpers\ErrorCode;
use App\Models\Token;
use App\Models\User;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PaginationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), [
            'page_index' => 'required|integer|min:1',
            'page_size'  => 'required|integer|min:1|max:' . config('api.max_page_size'),
        ]);

        if ($validator->fails()) {
            return failure(ErrorCode::FORM_VALIDATE_FAILED, $validator->errors()->toArray(), 422);
        }

        $request->page_index = intval($request->page_index);
        $request->page_size  = intval($request->page_size);

        return $next($request);
    }
}
