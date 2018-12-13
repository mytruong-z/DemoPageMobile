<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Product Filters in Codeigniter using Ajax</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href = "<?php echo base_url(); ?>asset/jquery-ui.css" rel = "stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>asset/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>




        </head>

<body>
<!-- Page Content -->
<div class="container">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

    <div class="row">
        <div class="col-md-3">
            <br />
            <br />
            <br />
            <div class="list-group">
                <h3>Price</h3>
                <input type="hidden" id="hidden_minimum_price" value="0" />
                <input type="hidden" id="hidden_maximum_price" value="65000" />
                <p id="price_show">1000 - 65000</p>
                <div id="price_range"></div>
            </div>
            <div class="list-group">
                <h3>Brand</h3>
                <?php
                foreach($brand_data->result_array() as $row)
                {
                    ?>
                    <div class="list-group-item checkbox">
                        <label><input type="checkbox" class="common_selector brand" value="<?php echo $row['product_brand']; ?>"  > <?php echo $row['product_brand']; ?></label>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="list-group">
                <h3>RAM</h3>
                <?php
                foreach($ram_data->result_array() as $row)
                {
                    ?>
                    <div class="list-group-item checkbox">
                        <label><input type="checkbox" class="common_selector ram" value="<?php echo $row['product_ram']; ?>" > <?php echo $row['product_ram']; ?> GB</label>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="list-group">
                <h3>Internal Storage</h3>
                <?php
                foreach($product_storage->result_array() as $row)
                {
                    ?>
                    <div class="list-group-item checkbox">
                        <label><input type="checkbox" class="common_selector storage" value="<?php echo $row['product_storage']; ?>"  > <?php echo $row['product_storage']; ?> GB</label>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="col-md-9">
            <h2 align="center">Product Filters in Codeigniter using Ajax</h2>
            <br />
            <div align="center" id="pagination_link">

            </div>
            <br />
            <br />
            <br />
            <div class="row filter_data">

            </div>
        </div>
    </div>

</div>
<style>
    #loading
    {
        text-align:center;
        background: url('<?php echo base_url(); ?>asset/loader.gif') no-repeat center;
        height: 150px;
    }
</style>


<script type='text/javascript'>
  $(document).ready(function(){

        filter_data(1);

        function filter_data(page)
        {
            $('.filter_data').html('<div id="loading" style="" ></div>');
            var action = 'fetch_data';
            var minimum_price = $('#hidden_minimum_price').val();
            var maximum_price = $('#hidden_maximum_price').val();
            var brand = get_filter('brand');
            var ram = get_filter('ram');
            var storage = get_filter('storage');
            $.ajax({

                url:"<?php echo base_url(); ?>product_filter/fetch_data/"+page,
                method:"POST",
                dataType:"JSON",

                data:{action:action, minimum_price:minimum_price, maximum_price:maximum_price, brand:brand, ram:ram, storage:storage,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',

                },
                success:function(data)
                {
                    // console.log(data);
                    $('.filter_data').html(data.product_list);
                    $('#pagination_link').html(data.pagination_link);
                },
                /*  error: function(data){
                      alert("fail"); */


            });

        }


        function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }

        $(document).on('click', '.pagination li a', function(event){
            event.preventDefault();
            var page = $(this).data('ci-pagination-page');
            filter_data(page);
        });

        $('.common_selector').click(function(){
            filter_data(1);
        });

        $('#price_range').slider({
            range:true,
            min:1000,
            max:65000,
            values:[1000,65000],
            step:500,
            stop:function(event, ui)
            {
                $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
                $('#hidden_minimum_price').val(ui.values[0]);
                $('#hidden_maximum_price').val(ui.values[1]);
                filter_data(1);
            }

        });

    });
  $.ajaxSetup({
      beforeSend: function(xhr, settings) {
          if (settings.data.indexOf('csrf_test_name') === -1) {
              settings.data += '&csrf_test_name=' + encodeURIComponent(Cookies.get('csrf_cookie_name'));
          }
      }
  });
</script>

</body>
</html>