<div class="mb-3">
    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $category->name) }}" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <input type="text" name="description" class="form-control"
           value="{{ old('description', $category->description) }}">
</div>
<div class="mb-3" style="max-width: 200px;">
    <label class="form-label">Thứ tự hiển thị</label>
    <input type="number" name="sort_order" class="form-control" min="0"
           value="{{ old('sort_order', $category->sort_order ?? 0) }}">
</div>
<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Hiển thị danh mục</label>
</div>
<button type="submit" class="btn btn-brand">{{ $submit }}</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
