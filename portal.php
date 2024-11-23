<style>
    header.masthead {
        position: relative;
        overflow: hidden; /* Ensure the video does not overflow */
    }

    /* Video styling */
    .masthead video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: 1; /* Make sure the video is behind the text */
        object-fit: cover; /* Cover the entire header */
        transform: translate(-50%, -50%); /* Center the video */
    }

    header.masthead .container {
        position: relative;
        z-index: 2; /* Ensure text is above the video */
        background: rgba(0, 0, 0, 0.6); /* Add a semi-transparent background for readability */
        padding: 60px 30px; /* Increased padding for a larger box */
        border-radius: 10px; /* Optional: Add rounded corners */
    }

    .masthead-subheading {
        font-size: 2.5rem; /* Increase font size */
        color: #fff; /* Ensure text is white */
    }

    .masthead-heading {
        font-size: 3.5rem; /* Increase font size */
        color: #fff; /* Ensure text is white */
    }
</style>

<!-- Masthead-->
<header class="masthead">
    <video autoplay muted loop>
        <source src="assets/home.mp4" type="video/mp4"> <!-- Change this to the path of your video -->
        Your browser does not support HTML5 video.
    </video>
    <div class="container">
        <div class="masthead-subheading">Welcome</div>
        <div class="masthead-heading text-uppercase">Explore our Tour Packages</div>
        <a class="btn btn-primary btn-xl text-uppercase" href="#home">View Tours</a>
    </div>
</header>


<!-- Services-->
<section class="page-section bg-dark" id="home">
    <div class="container">
        <h2 class="text-center">Tour Packages</h2>
        <div class="d-flex w-100 justify-content-center">
            <hr class="border-warning" style="border:3px solid" width="15%">
        </div>
        <div class="row">
            <?php
            $packages = $conn->query("SELECT * FROM `packages` order by rand() limit 3");
            while ($row = $packages->fetch_assoc()): 
                $cover = '';
                if (is_dir(base_app.'uploads/package_'.$row['id'])) {
                    $img = scandir(base_app.'uploads/package_'.$row['id']);
                    $k = array_search('.', $img);
                    if ($k !== false) unset($img[$k]);
                    $k = array_search('..', $img);
                    if ($k !== false) unset($img[$k]);
                    $cover = isset($img[2]) ? 'uploads/package_'.$row['id'].'/'.$img[2] : "";
                }
                $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));

                $review = $conn->query("SELECT * FROM `rate_review` where package_id='{$row['id']}'");
                $review_count = $review->num_rows;
                $rate = 0;
                while ($r = $review->fetch_assoc()) {
                    $rate += $r['rate'];
                }
                if ($rate > 0 && $review_count > 0)
                    $rate = number_format($rate/$review_count, 0, "", "");
            ?>
                <div class="col-md-4 p-4">
                    <div class="card w-100 rounded-0">
                        <img class="card-img-top" src="<?php echo validate_image($cover) ?>" alt="<?php echo $row['title'] ?>" height="200rem" style="object-fit:cover">
                        <div class="card-body">
                            <h5 class="card-title truncate-1 w-100"><?php echo $row['title'] ?></h5><br>
                            <div class="w-100 d-flex justify-content-start">
                                <div class="stars stars-small">
                                    <input disabled class="star star-5" id="star-5" type="radio" name="star" <?php echo $rate == 5 ? "checked" : '' ?>/> <label class="star star-5" for="star-5"></label>
                                    <input disabled class="star star-4" id="star-4" type="radio" name="star" <?php echo $rate == 4 ? "checked" : '' ?>/> <label class="star star-4" for="star-4"></label>
                                    <input disabled class="star star-3" id="star-3" type="radio" name="star" <?php echo $rate == 3 ? "checked" : '' ?>/> <label class="star star-3" for="star-3"></label>
                                    <input disabled class="star star-2" id="star-2" type="radio" name="star" <?php echo $rate == 2 ? "checked" : '' ?>/> <label class="star star-2" for="star-2"></label>
                                    <input disabled class="star star-1" id="star-1" type="radio" name="star" <?php echo $rate == 1 ? "checked" : '' ?>/> <label class="star star-1" for="star-1"></label>
                                </div>
                            </div>
                            <p class="card-text truncate"><?php echo $row['description'] ?></p>
                            <div class="w-100 d-flex justify-content-end">
                                <a href="./?page=view_package&id=<?php echo md5($row['id']) ?>" class="btn btn-sm btn-flat btn-warning">View Package <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="d-flex w-100 justify-content-end">
            <a href="./?page=packages" class="btn btn-flat btn-warning mr-4">Explore Package <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- About-->
<section class="page-section" id="about">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">About Us</h2>
        </div>
        <div>
            <div class="card w-100">
                <div class="card-body">
                    <?php echo file_get_contents(base_app.'about.html') ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact-->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Contact Us</h2>
            <h3 class="section-subheading text-muted">Send us a message for inquiries.</h3>
        </div>
        <form id="contactForm">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="name" name="name" type="text" placeholder="Your Name *" required />
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="email" name="email" type="email" placeholder="Your Email *" data-sb-validations="required,email" />
                    </div>
                    <div class="form-group mb-md-0">
                        <input class="form-control" id="subject" name="subject" type="subject" placeholder="Subject *" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" id="message" name="message" placeholder="Your Message *" required></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center"><button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Send Message</button></div>
        </form>
    </div>
</section>

<script>
$(function(){
    $('#contactForm').submit(function(e){
        e.preventDefault()
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_inquiry",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occurred", 'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Inquiry sent", 'success')
                    $('#contactForm').get(0).reset()
                } else {
                    console.log(resp)
                    alert_toast("an error occurred", 'error')
                    end_loader()
                }
            }
        })
    })
})
</script>
