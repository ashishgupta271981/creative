<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="main-container">

  
  <!-- Footer Section Strat ================================================== -->
  
  <div class="well bg-red-dark">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h3><i class="fa fa-home"></i></h3>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Footer Section Strat ================================================== -->
  <div class="content-area blue-area">
    <div class="container">
    <div class="">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
          <div id="myCarousel" class="carousel slide " data-ride="carousel"> <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"> <img src="<?php echo site_url('assets/img/back-logo-icon-27727.png');?>" width="25"> <span class="sr-only">Previous</span> </a>
            <div class="carousel-inner overflow-scroll-y-550p" role="listbox">
              <div class="item active">
                <h3><strong>Looking For ?</strong></h3>
                <p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                 <div class="row text-center">
                  <?php 
                  foreach($categories as $value){ 
                    ?>
                  <div class="col-sm-4 col-sm-6"> <a href="#" id="categorydiv_<?php echo $value['id'];?>" class="categorydiv" data-rel="<?php echo $value['id'];?>" data-rel-name="<?php echo $value['name'];?>">
                    <div class="thumbnail "> <img src="<?php echo site_url('assets/img/icon/'.$value['featured_image']);?>" alt="...">
                      <div class="caption">
                        <p><?php echo $value['name'];?></p>
                      </div>
                    </div>
                    </a> </div>
                  <?php } ?>
               </div>

               
              </div>
              <div class="item">
                <h3><strong>What type of work do you require?</strong></h3>
                <p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
               <div class="row text-center" id="subcategorydiv">
                 
               </div>

              </div>
              <div class="item">
                <h3><strong>Skill required?</strong></h3>
                <p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                <form class="form-select">
                  <div class="col-sm-12">
                     <div id="skilldiv"></div>
                   
              
                          </div>
                </form>
              </div>
              <div class="item">
                <h3><strong>Budget of Your Project</strong></h3>
                <p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                <form class="form-select">
                  <div class="col-sm-7">
                    <?php 
                  foreach($budgets as $budget){ 
                    ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="budget_val" id="budget_val" value="<?php echo $budget['budget'];?>" checked>
                        <?php echo $budget['budget'];?></label>
                    </div>
                    <?php }?>

                  </div>
                </form>
              </div>
              <div class="item">
                <h3><strong>Your prefered location</strong></h3>
                <p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                <form class="form-select">
                   <div class="col-sm-7">
                    <label class="dropdown">
                      <select class="form-control" id="country" name="country">
                       <?php if(is_array($countries) && !empty($countries)){
                        foreach($countries as $key=>$country){ 
                       ?>
                        <option value="<?php echo $key;?>" data-rel-name="<?php echo $country['name'];?>"><?php echo $country['name'];?></option>
					   <?php }}?>

                      </select>
                    </label>
                  </div>
                 <div class="col-sm-7" style="display:none" id="statediv">
                    <label class="dropdown">
                      <select class="form-control" id="state" name="state">
                        <option>Haryana</option>
                        
                      </select>
                    </label>
                  </div>
                  <div class="col-sm-7" style="display:none" id="citydiv">
                    <label class="dropdown">
                      <select class="form-control" id="city" name="city">
                        <option>Gurgaon</option>
                     
                      </select>
                    </label>
                  </div>

                </form>
              </div>
            </div>
            
           
             <div class="col-sm-10">
             <div class="row">
             <a class=" " href="#myCarousel" role="button" data-slide="next" id="myCarouselnext">
            <button class="btn btn-danger">Next</button>
            <span class="sr-only">Next</span> </a> 
          </div>
          </div>
            
            <!-- Indicators -->
            <div class="col-sm-2">
            <ol class="carousel-indicators">
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1"></li>
              <li data-target="#myCarousel" data-slide-to="2"></li>
              <li data-target="#myCarousel" data-slide-to="3"></li>
              <li data-target="#myCarousel" data-slide-to="4"></li>
            </ol>
            </div>
            
          
           
          </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " id="rightdiv" style="display: none;">
          <div class="well list-color overflow-scroll-y-450p">
            <h4 class="primary-color">Search Summary</h4>
            <p><strong>Lets Get Started?</strong></p>
            <li><span><i>Full Time</i></span></li>
            <p><strong>Looking for?</strong></p>
            <li><span><i id="right_category">-------------------</i></span></li>
            <p><strong>What type of work do you require?</strong></p>
            <l><span><i id="right_subcategory">--------------------</i></span></li>
            <p> <strong>Skill required?</strong></p>
            <li><span><i id="right_skills">-------------------</i></span></li>
            <p><strong>Project Budget?</strong></p>
            <li><span><i id="right_budget">----------------</i></span></li>
            <p> <strong>Your prefered location</strong></p>
            <li><span><i id="right_location">-----------------------</i></span></li>
            <br/>
            <p id="lets_go" style="display:none"><a href="#" class="btn btn-danger">Lets Go</a></p>
          </div>
        </div>
     
        <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="img/back-logo-icon-27727.png" width="25"> </div>--> 
        
      </div>
    </div>
  </div>



<!-- /.container --> 
</div>
<!--<a href="#" class="back-to-top wow bounceInRight animated"><img src="img/back-to-top-dark.png" class="img-responsive"></a> -->
