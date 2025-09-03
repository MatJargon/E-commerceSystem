@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Category Information</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.categories') }}">
                        <div class="text-tiny">Categories</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Edit Category</div>
                </li>
            </ul>
        </div>

        <!-- edit-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.category.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $category->id }}" />

                <fieldset class="name">
                    <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Category name" name="name"
                        value="{{ $category->name }}" required>
                </fieldset>
                @error('name')<span class="alert-danger">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Category Slug" name="slug"
                        value="{{ $category->slug }}" required readonly>
                </fieldset>
                @error('slug')<span class="alert-danger">{{ $message }}</span>@enderror

                <fieldset>
                    <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                    <input id="myFile" type="file" name="image">
                    <div id="imgprevious" style="{{ $category->image ? '' : 'display:none;' }}">
                        @if($category->image)
                            <img src="{{ asset('uploads/categories/' . $category->image) }}" alt="Preview"
                                style="max-width:150px;">
                        @else
                            <img src="" alt="Preview" style="max-width:150px;">
                        @endif
                    </div>
                </fieldset>
                @error('image')<span class="alert-danger">{{ $message }}</span>@enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function () {
        $("#myFile").on("change", function () {
            const [file] = this.files;
            if (file) {
                $("#imgprevious img").attr('src', URL.createObjectURL(file));
                $("#imgprevious").show();
            }
        });

        $("input[name='name']").on("change", function () {
            let nameValue = $(this).val().trim();
            if (nameValue !== "") {
                let slug = StringToSlug(nameValue);

                // Add suffix so slug is unique
                let uniqueSuffix = Date.now();
                slug = slug + "-" + uniqueSuffix;

                $("input[name ='slug']").val(slug);
            }
        });
    });

    function StringToSlug(str) {
        return str
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    }
</script>
@endpush
