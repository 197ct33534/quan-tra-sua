<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $product->name) }}" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">— Chọn —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Giá (đ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                       min="0" step="1000" value="{{ old('price', $product->price) }}" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Thứ tự</label>
                <input type="number" name="sort_order" class="form-control" min="0"
                       value="{{ old('sort_order', $product->sort_order ?? 0) }}">
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1"
                   {{ old('is_available', $product->is_available ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_available">Còn hàng (hiển thị bán)</label>
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="has_options" id="has_options" value="1"
                   {{ old('has_options', $product->has_options ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="has_options">Cho chọn size & topping (dành cho đồ uống)</label>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Hình ảnh</label>
        <div class="border rounded p-2 text-center mb-2">
            <img src="{{ $product->image_url }}" class="img-fluid rounded" style="max-height: 180px;" alt="">
        </div>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">JPG/PNG, tối đa 2MB. Để trống nếu không đổi.</small>
    </div>
</div>
<hr>
<button type="submit" class="btn btn-brand">{{ $submit }}</button>
<a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
