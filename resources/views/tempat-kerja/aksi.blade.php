<div class="btn-group"><a href="{{ route($attribute['link'].'edit',$data->id) }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-pencil-alt"></i></a><button class="btn btn-sm btn-icon btn-danger hapus" data-id="{{ $data->id }}"><i class="fas fa-trash-alt"></i></button><a href="{{ route($attribute['link'].'show',$data->id) }}" class="btn btn-sm btn-icon btn-primary"><i class="fa-solid fa-location-dot"></i></a></div>