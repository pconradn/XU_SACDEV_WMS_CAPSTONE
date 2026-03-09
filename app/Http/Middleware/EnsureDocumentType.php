<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ProjectDocument;
use Symfony\Component\HttpFoundation\Response;

class EnsureDocumentType
{
    public function handle(Request $request, Closure $next, string $formCode): Response
    {

        $document = $request->route('document');

        if (!$document instanceof ProjectDocument) {
            abort(404);
        }

        if ($document->formType->code !== $formCode) {
            abort(403, 'Invalid document type for this route.');
        }

        return $next($request);
    }
}