<!-- Nav tabs -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">全部</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="g78naxx2hmt-tab" data-toggle="tab" href="#g78naxx2hmt" role="tab" aria-controls="g78naxx2hmt" aria-selected="false">決戰！平安京</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="g83tw-tab" data-toggle="tab" href="#g83tw" role="tab" aria-controls="g83tw" aria-selected="false">荒野行動</a>
  </li>
</ul>


<div id="accordion">
  <?foreach($news->result() as $row):?>
  <div class="card" data-site="<?=$row->target?>">
    <div class="card-header" id="heading<?=$row->id?>">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?=$row->id?>" aria-expanded="true" aria-controls="collapse<?=$row->id?>">
           <i class="fas fa-question" ></i> <?=$row->title;?>
        </button>
      </h5>
    </div>

    <div id="collapse<?=$row->id?>" class="collapse show" aria-labelledby="heading<?=$row->id?>" data-parent="#accordion">
      <div class="card-body">
        <i class="fas fa-hand-point-down" ></i><?=$row->content;?>
      </div>
    </div>
  </div>
  <?endforeach;?>
</div>
