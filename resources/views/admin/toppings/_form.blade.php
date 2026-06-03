<div class="mb-3">
    <label class="form-label">Tên topping <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $topping->name) }}" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3" style="max-width: 220px;">
    <label class="form-label">Giá (đ) <span class="text-danger">*</span></label>
    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
           min="0" step="1000" value="{{ old('price', $topping->price ?? 0) }}" required>
    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $topping->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Kích hoạt</label>
</div>
<button type="submit" class="btn btn-brand">{{ $submit }}</button>
<a href="{{ route('admin.toppings.index') }}" class="btn btn-outline-secondary">Hủy</a>
