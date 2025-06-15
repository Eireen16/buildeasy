@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Material Categories Management</h1>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-1"></i>Add Category
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                        <i class="fas fa-plus me-1"></i>Add Subcategory
                    </button>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Categories Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Categories & Subcategories</h6>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="row">
                            @foreach($categories as $category)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border-left-primary h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 font-weight-bold text-primary">{{ $category->category }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="editCategory({{ $category->id }}, '{{ $category->category }}')">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" onclick="deleteCategory({{ $category->id }}, '{{ $category->category }}')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($category->subCategories->count() > 0)
                                                <div class="list-group list-group-flush">
                                                    @foreach($category->subCategories as $subCategory)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                            <span class="text-sm">{{ $subCategory->subcategory }}</span>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#" onclick="editSubCategory({{ $subCategory->id }}, '{{ $subCategory->subcategory }}')">
                                                                            <i class="fas fa-edit me-2"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger" href="#" onclick="deleteSubCategory({{ $subCategory->id }}, '{{ $subCategory->subcategory }}')">
                                                                            <i class="fas fa-trash me-2"></i>Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">No subcategories found</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Categories Found</h5>
                            <p class="text-muted">Start by adding your first category</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.subcategories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Choose a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory" class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" id="subcategory" name="subcategory" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_category" name="category" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subcategory Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSubCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_subcategory" class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" id="edit_subcategory" name="subcategory" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete forms (hidden) -->
<form id="deleteCategoryForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="deleteSubCategoryForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function editCategory(id, name) {
    document.getElementById('edit_category').value = name;
    document.getElementById('editCategoryForm').action = `{{ url('/admin/categories') }}/${id}`;
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function editSubCategory(id, name) {
    document.getElementById('edit_subcategory').value = name;
    document.getElementById('editSubCategoryForm').action = `{{ url('/admin/subcategories') }}/${id}`;
    new bootstrap.Modal(document.getElementById('editSubCategoryModal')).show();
}

function deleteCategory(id, name) {
    if (confirm(`Are you sure you want to delete the category "${name}"? This will also delete all its subcategories.`)) {
        const form = document.getElementById('deleteCategoryForm');
        form.action = `{{ url('/admin/categories') }}/${id}`;
        form.submit();
    }
}

function deleteSubCategory(id, name) {
    if (confirm(`Are you sure you want to delete the subcategory "${name}"?`)) {
        const form = document.getElementById('deleteSubCategoryForm');
        form.action = `{{ url('/admin/subcategories') }}/${id}`;
        form.submit();
    }
}
</script>
@endsection