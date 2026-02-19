<div class="portlet light portlet-fit">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-list font-red"></i>
            <span class="caption-subject font-red bold uppercase">
                Income Category List
            </span>
        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a  data-toggle="modal" data-target="#addCategory" class="btn sbold dark"> Add New
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <!-- BEGIN FORM-->

        <table class="table table-striped table-bordered table-hover order-column" id="expense">
            <thead>
            <tr>
                <th class="desktop"> Title</th>
                <th class="desktop"> Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->title }}</td>
                    <td>
                        @if($category->detail_id == $gymSettings->detail_id)
                        <a class="btn btn-sm btn-primary" data-toggle="modal"
                           data-target="#editModal{{$category->uuid}}">Edit
                            <i class="fa fa-edit"></i>
                        </a>
                        {{--edit category--}}
                        <div class="modal fade" id="editModal{{$category->uuid}}" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" style="font-weight: 600;">
                                            Edit Income Category
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {{ html()->form->open(['id'=>'edit-category-'.$category->uuid,'class'=>'ajax-form']) !!}
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-md-line-input ">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Category Name"
                                                               name="title" value="{{ $category->title }}" required>
                                                        <label for="title">Category Name<span class="required"
                                                                                              aria-required="true"> * </span></label>
                                                        <span class="help-block">Add Category Name</span>
                                                        <i class="fa fa-sticky-note-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                        data-style="zoom-in" onclick="addUpdateCategory('{{$category->uuid}}')">
                                                    <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ html()->form->close() !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- END FORM-->
    </div>
</div>


{{--add category--}}
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-weight: 600;">
                    Add Income Category
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ html()->form->open(['id'=>'create-category','class'=>'ajax-form']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-md-line-input ">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Category Name"
                                       name="title" value="" required>
                                <label for="title">Category Name<span class="required"
                                                                      aria-required="true"> * </span></label>
                                <span class="help-block">Add Category Name</span>
                                <i class="fa fa-sticky-note-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                data-style="zoom-in" onclick="addUpdateCategory()">
                            <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
            {{ html()->form->close() !!}
        </div>
    </div>
</div>
