<?php
namespace App\Http\Controllers\Admin\Category;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\Subcategory;
use App\Models\Category\TopList;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class TopListController extends Controller
{
    public function index($category_id)
    {
        $category = Category::with('topList.product')->findOrFail($category_id);
        $topLists = $category->topList;
        return view('admin.pages.top-list.index', compact('category', 'topLists'));
    }

    // Show form to create a top list item for a specific category
    public function create($category_id)
    {
        $category = Category::findOrFail($category_id);
        $products = Product::main()->get();
        return view('admin.pages.top-list.create', compact('category', 'products'));
    }

    // Store a new top list item for a specific category
    public function store(Request $request, $category_id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'position' => 'required|integer|min:1',
        ]);

        $category = Category::findOrFail($category_id);

        TopList::create([
            'category_id' => $category->id,
            'product_id' => $request->product_id,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.category.top-list.index', $category_id)->with('success', 'Top List item created successfully.');
    }

    // Show form to edit a top list item
    public function edit($category_id, $id)
    {
        $category = Category::findOrFail($category_id);
        $topList = TopList::findOrFail($id);
        $products = Product::main()->get();

        return view('admin.pages.top-list.edit', compact('category', 'topList', 'products'));
    }

    // Update an existing top list item for a specific category
    public function update(Request $request, $category_id, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'position' => 'required|integer|min:1',
        ]);

        $topList = TopList::findOrFail($id);
        $topList->update($request->all());

        return redirect()->route('admin.category.top-list.index', $category_id)->with('success', 'Top List item updated successfully.');
    }

    // Delete a top list item
    public function destroy($category_id, $id)
    {
        $topList = TopList::findOrFail($id);
        $topList->delete();

        return redirect()->route('admin.category.top-list.index', $category_id)->with('success', 'Top List item deleted successfully.');
    }
}
