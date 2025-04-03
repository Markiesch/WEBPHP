<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessEditorController extends Controller
{
    public function index(): View
    {
        $business = Business::where("user_id", auth()->id())->firstOrFail();

        if (!$business) {
            // Fallback if user has no business yet
            return view('seller.business-editor', [
                'business' => null,
                'blocks' => collect([]),
            ]);
        }

        return view('seller.business-editor', [
            'business' => $business,
            'blocks' => $business->blocks,
        ]);
    }

    public function updateBlock(Request $request, BusinessBlock $block): RedirectResponse
    {
        // Validate ownership
        if ($block->business->user_id !== auth()->id()) {
            abort(403);
        }

        $validatedData = $request->validate([
            'content' => 'required|array',
        ]);

        $block->update($validatedData);

        return redirect()->route('business.index')->with('success', 'Block updated successfully');
    }

    public function updateOrder(Request $request): RedirectResponse
    {
        $business = auth()->user()->business;

        if (!$business) {
            abort(404);
        }

        $validatedData = $request->validate([
            'blocks' => 'required|array',
            'blocks.*' => 'numeric|exists:business_blocks,id',
        ]);

        foreach ($validatedData['blocks'] as $order => $blockId) {
            BusinessBlock::where('id', $blockId)
                ->where('business_id', $business->id)
                ->update(['order' => $order + 1]);
        }

        return redirect()->route('business.index')->with('success', 'Block order updated');
    }

    public function createBlock(Request $request): RedirectResponse
    {
        $business = auth()->user()->business;

        if (!$business) {
            abort(404);
        }

        $validatedData = $request->validate([
            'type' => 'required|string',
        ]);

        // Get the highest order and add 1
        $maxOrder = $business->blocks()->max('order') ?? 0;

        // Create default content based on block type
        $content = $this->getDefaultContentForType($validatedData['type']);

        BusinessBlock::create([
            'business_id' => $business->id,
            'type' => $validatedData['type'],
            'content' => $content,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('business.index')->with('success', 'New block added');
    }

    public function deleteBlock(BusinessBlock $block): RedirectResponse
    {
        // Validate ownership
        if ($block->business->user_id !== auth()->id()) {
            abort(403);
        }

        $block->delete();

        return redirect()->route('business.index')->with('success', 'Block deleted successfully');
    }

    private function getDefaultContentForType(string $type): array
    {
        return match($type) {
            'intro_text' => [
                'title' => 'New Section Title',
                'text' => '<p>Add your text here</p>',
            ],
            'featured_ads' => [
                'title' => 'Featured Listings',
                'count' => 3,
            ],
            'image' => [
                'title' => 'Image Title',
                'url' => '',
                'alt' => 'Description',
                'caption' => '',
                'fullWidth' => false,
            ],
            default => [],
        };
    }
}
