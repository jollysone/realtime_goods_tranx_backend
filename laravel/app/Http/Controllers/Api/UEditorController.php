<?php
/**
 * UEditor
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UEditor\UEditorUtils;
use Illuminate\Http\Request;


class UEditorController extends Controller
{
    public function main(Request $request)
    {
        if ($request->isMethod('OPTIONS')) {
            return response('', 200);
        }

        $editor = new UEditorUtils();
        $res    = $editor->main();

        return response($res, 200);
    }
}