<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }
    
    $query = "
        SELECT 
            *
        FROM works
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $works = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM contents";
    $stmt = $conn->query($sql);
    $contents = $stmt->fetchAll();
    $contentsById = [];
    foreach ($contents as $content) {
        $contentsById[$content['content_id']] = $content;
    }
    // var_dump($contentsById);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/cms.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <div class="admin-sidebar">
            <h1>
                <p class="text-gold">Sargento</p>
                <p class="text-gold">Upholstery</p>
            </h1>
            <ul class="admin-nav">
                <a href="./dashboard.php" class="admin-link fill-icon" data-page="dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z"/></svg>
                    <svg class="hovered" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="./orders.php" class="admin-link" data-page="orders">
                    <svg class="unhovered" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="shopping-bag-02">
                        <path id="Icon" d="M16.0004 9V6C16.0004 3.79086 14.2095 2 12.0004 2C9.79123 2 8.00037 3.79086 8.00037 6V9M3.59237 10.352L2.99237 16.752C2.82178 18.5717 2.73648 19.4815 3.03842 20.1843C3.30367 20.8016 3.76849 21.3121 4.35839 21.6338C5.0299 22 5.94374 22 7.77142 22H16.2293C18.057 22 18.9708 22 19.6423 21.6338C20.2322 21.3121 20.6971 20.8016 20.9623 20.1843C21.2643 19.4815 21.179 18.5717 21.0084 16.752L20.4084 10.352C20.2643 8.81535 20.1923 8.04704 19.8467 7.46616C19.5424 6.95458 19.0927 6.54511 18.555 6.28984C17.9444 6 17.1727 6 15.6293 6L8.37142 6C6.82806 6 6.05638 6 5.44579 6.28984C4.90803 6.54511 4.45838 6.95458 4.15403 7.46616C3.80846 8.04704 3.73643 8.81534 3.59237 10.352Z" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>Orders</span>
                </a>
                <a href="./payment.php" class="admin-link" data-page="payment">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="folder">
                        <path id="Icon" d="M13 7L11.8845 4.76892C11.5634 4.1268 11.4029 3.80573 11.1634 3.57116C10.9516 3.36373 10.6963 3.20597 10.4161 3.10931C10.0992 3 9.74021 3 9.02229 3H5.2C4.0799 3 3.51984 3 3.09202 3.21799C2.71569 3.40973 2.40973 3.71569 2.21799 4.09202C2 4.51984 2 5.0799 2 6.2V7M2 7H17.2C18.8802 7 19.7202 7 20.362 7.32698C20.9265 7.6146 21.3854 8.07354 21.673 8.63803C22 9.27976 22 10.1198 22 11.8V16.2C22 17.8802 22 18.7202 21.673 19.362C21.3854 19.9265 20.9265 20.3854 20.362 20.673C19.7202 21 18.8802 21 17.2 21H6.8C5.11984 21 4.27976 21 3.63803 20.673C3.07354 20.3854 2.6146 19.9265 2.32698 19.362C2 18.7202 2 17.8802 2 16.2V7Z" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>Payment</span>
                </a>
                <a href="./cms.php" class="admin-link fill-icon" data-page="cms">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="edit_document_24dp_FILL0_wght400_GRAD0_opsz24 1">
                        <path id="Vector" d="M14 22V18.925L19.525 13.425C19.675 13.275 19.8417 13.1667 20.025 13.1C20.2083 13.0333 20.3917 13 20.575 13C20.775 13 20.9667 13.0375 21.15 13.1125C21.3333 13.1875 21.5 13.3 21.65 13.45L22.575 14.375C22.7083 14.525 22.8125 14.6917 22.8875 14.875C22.9625 15.0583 23 15.2417 23 15.425C23 15.6083 22.9667 15.7958 22.9 15.9875C22.8333 16.1792 22.725 16.35 22.575 16.5L17.075 22H14ZM15.5 20.5H16.45L19.475 17.45L19.025 16.975L18.55 16.525L15.5 19.55V20.5ZM6 22C5.45 22 4.97917 21.8042 4.5875 21.4125C4.19583 21.0208 4 20.55 4 20V4C4 3.45 4.19583 2.97917 4.5875 2.5875C4.97917 2.19583 5.45 2 6 2H14L20 8V11H18V9H13V4H6V20H12V22H6ZM19.025 16.975L18.55 16.525L19.475 17.45L19.025 16.975Z" fill="#C4CAD4"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z"/></svg>
                    <span>Content</span>
                </a>
                <a href="./chat.php" class="admin-link fill-icon" id="chatSystemLink" data-page="chat">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#C4CAD4"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
                    <span>Chat</span>
                </a>
            </ul>
            <ul class="admin-extra">
                <a href="../index.php" class="admin-link fill-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="home_24dp_FILL0_wght400_GRAD0_opsz24 1">
                        <path id="Vector" d="M6 19H9V13H15V19H18V10L12 5.5L6 10V19ZM4 21V9L12 3L20 9V21H13V15H11V21H4Z" fill="#C4CAD4"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#111"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
                    <span>Back To Home</span>
                </a>
                <li class="admin-link">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="Icons">
                        <path id="Icon" d="M11.1956 2.87988C16.4976 2.87988 20.7956 7.17795 20.7956 12.4799C20.7956 17.7818 16.4976 22.0799 11.1956 22.0799M8.79557 16.3199L4.95557 12.4799M4.95557 12.4799L8.79557 8.63988M4.95557 12.4799H16.9556" stroke="#C4CAD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>Logout</span>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="tab-selector">
                <span class="tab-button active" data-tab="home">Home</span>
                <span class="tab-button" data-tab="services-craftsmanship">Craftsmanship</span>
                <span class="tab-button" data-tab="services-works">Works</span>
                <span class="tab-button" data-tab="order">Order</span>
                <span class="tab-button" data-tab="testimonial">Testimonial</span>
                <span class="tab-button" data-tab="about">About Us</span>
                <span class="tab-button" data-tab="contact">Contact Us</span>
            </div> 
            <div class="active-tab">
                <div class="tab" data-page="home">
                    <div class="featured">
                        <h2 class="featured-title">
                            Well-crafted & Timeless
                            <span>We Bring Your Vision to Life: Crafting Excellence in Every Design</span>
                        </h2>
                        <a href="order.php" class="btn btn-black">Get a free quote</a>
                    </div>
                    <div class="product">
                        <h2 class="product-category-title editable short-text" data-id="HOMECRAFTSTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMECRAFTSTITLE"]["content_text"])) ?></h2>
                        <p class="product-category-intro editable long-text" data-id="HOMECRAFTSTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMECRAFTSTEXT"]["content_text"])) ?></p>
                        <div class="product-categories">
                            <a class="product-category" id="category-loveseat">
                                <img src="/websiteimages/loveseatsimg.png" alt="">
                                <p>Loveseats</p>
                            </a>
                            <a class="product-category" id="category-sofa">
                                <img src="/websiteimages/sofasimg.png" alt="">
                                <p>Sofas</p>
                            </a>
                            <a class="product-category" id="category-bed">
                                <img src="/websiteimages/bedsimage.png" alt="">
                                <p>Beds</p>
                            </a>
                            <a class="product-category" id="category-armchair">
                                <img src="/websiteimages/chairsimg.png" alt="">
                                <p>Chairs</p>
                            </a>
                            <a class="product-category" id="category-ottoman">
                                <img src="/websiteimages/ottomanimg.png" alt="">
                                <p>Ottomans</p>
                            </a>
                            <a class="product-category" id="category-custom">
                                <img src="/websiteimages/customordersimg.png" alt="">
                                <p>Custom</p>
                            </a>
                        </div>
                    </div>
                    <div class="testimonials">
                        <h2 class="testimonials-title editable short-text" data-id="HOMETESTIMONIALTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMETESTIMONIALTITLE"]["content_text"])) ?></h2>
                        <div class="reviews">
                            <div class="review">
                                <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
                                <p>
                                    <p class="editable long-text" data-id="HOMETESTIMONIALCOMMENT1"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMETESTIMONIALCOMMENT1"]["content_text"])) ?></p>
                                    <br><br><span class="client-name editable short-text" data-id="HOMETESTIMONIALAUTHOR1"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMETESTIMONIALAUTHOR1"]["content_text"])) ?></span></p>
                                <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
                            </div>
                            <div class="review">
                                <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
                                <p class="editable long-text" data-id="HOMETESTIMONIALCOMMENT2"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMETESTIMONIALCOMMENT2"]["content_text"])) ?></p>
                                <br><br><span class="client-name editable short-text" data-id="HOMETESTIMONIALAUTHOR2"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMETESTIMONIALAUTHOR2"]["content_text"])) ?></span></p>
                                <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
                            </div>
                        </div>
                        <a href="testimonials.php" class="btn btn-black">More Reviews</a>
                    </div>
                    <div class="about-us">
                        <img src="<?= $contentsById["HOMETEAMPICTURE"]["image"]?>" alt="" class="team-picture">
                        <h2 class="text-gold editable short-text" data-id="HOMEABOUTTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMEABOUTTITLE"]["content_text"])) ?></h2>
                        <p class="business-history editable long-text" data-id="HOMEABOUTTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["HOMEABOUTTEXT"]["content_text"])) ?></p>
                        <a href="about_us.php" class="btn btn-white">Learn More</a>
                    </div>
                </div>
                <div class="tab" data-page="services-craftsmanship">
                    <img src="/websiteimages/2mensofa.png" alt="Photo of 2 men carrying a Sofa" class="intro-image">
                    <div class="intro-section">
                        <h2 class="intro-title editable short-text" data-id="CRAFTSHEADERTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSHEADERTITLE"]["content_text"])) ?></h2>
                        <p class="intro-info editable long-text" data-id="CRAFTSHEADERTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSHEADERTEXT"]["content_text"])) ?></p>
                        <a href="order.php" class="btn btn-black">Get Free Quote</a>
                    </div>
                    <div class="services_craftmanship_card_container">
                        <div class="services_craftmanship_card">
                            <img src="/websiteimages/malopit.png" alt="wood crafting" div class="services_craftmanship_card_1_image">
                            <div class="services_craftmanship_card_text"> 
                                <h1 class="text-gold editable short-text" data-id="CRAFTSCARDTITLE1"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE1"]["content_text"])) ?></h1>
                                <p class="editable long-text" data-id="CRAFTSCARDTEXT1"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT1"]["content_text"])) ?></p>
                            </div>
                        </div>
                        <div class="services_craftmanship_card big">
                            <div class="services_craftmanship_card_text ta-right"> 
                                <h1 class="text-gold editable short-text" data-id="CRAFTSCARDTITLE2"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE2"]["content_text"])) ?></h1>
                                <p class="editable long-text" data-id="CRAFTSCARDTEXT2"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT2"]["content_text"])) ?></p>
                            </div>
                            <img src="/websiteimages/cube.png" alt="wood crafting" div class="services_craftmanship_card_2_image">
                        </div>
                        <div class="services_craftmanship_card">
                            <img src="/websiteimages/fabric cut (1).png" alt="wood crafting" div class="services_craftmanship_card_3_image">
                            <div class="services_craftmanship_card_text"> 
                                <h1 class="text-gold editable short-text" data-id="CRAFTSCARDTITLE3"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE3"]["content_text"])) ?></h1>
                                <p class="editable long-text" data-id="CRAFTSCARDTEXT3"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT3"]["content_text"])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="services_craftmanship_text_2">
                        <p class="editable long-text" data-id="CRAFTSFOOTERTEXT" data-id="CRAFTSFOOTERTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSFOOTERTEXT"]["content_text"])) ?></p>
                    </div>
                    <img src="/websiteimages/Divider.png" div class="services_craftmanship_text_divider_img">
                    <div class="services_craftmanship_hero_image_2">
                        <h1>
                            Explore Our Masterpieces
                        </h1>
                        <a href="services_works.php" class="btn btn-black">See More</a>
                    </div>

                </div>
                <div class="tab" data-page="services-works">
                    <img src="/websiteimages/services-works-heroimage-img.jpg" alt="Sofa" div class="services-works-heroimage">
                    <div class="intro-section">
                        <h2 class="intro-title editable short-text" data-id="WORKSHEADERTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["WORKSHEADERTITLE"]["content_text"])) ?></h2>
                        <p class="intro-info editable long-text" data-id="WORKSHEADERTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["WORKSHEADERTEXT"]["content_text"])) ?></p>
                        <a href="order.php" class="btn btn-black">Get Free Quote</a>
                    </div> 
                    <div class="product-gallery">
                        <div class="selector-container">
                            <select name="type-selector" id="type-selector" onchange="filterGallery()">
                                <option selected value="all">All</option>
                                <option value="bed">Beds</option>
                                <option value="armchair">Armchairs</option>
                                <option value="custom">Custom</option>
                                <option value="loveseat">Loveseats</option>
                                <option value="ottoman">Ottomans</option>
                                <option value="sofa">Sofas</option>
                                <option value="cleopatra">Cleopatras</option>
                            </select>
                            <select name="color-selector" id="color-selector" onchange="filterGallery()">
                                <option selected value="all">All</option>
                                <option value="black">Black</option>
                                <option value="blue">Blue</option>
                                <option value="brown">Brown</option>
                                <option value="cyan">Cyan</option>
                                <option value="gray">Gray</option>
                                <option value="orange">Orange</option>
                                <option value="pink">Pink</option>
                                <option value="purple">Purple</option>
                                <option value="red">Red</option>
                                <option value="white">White</option>
                            </select>
                        </div>
                        <div class="gallery-section">
                            <div class="add-image gallery-item">
                                <div class="plus">
                                    <img src="/websiteimages/icons/add-icon.svg" alt="">
                                </div>
                            </div>
                            <?php
                                $sql = "
                                    SELECT
                                        *
                                    FROM works
                                    ORDER BY works_id DESC
                                ";
                                $stmt = $conn->query($sql);
                                // <img src="/websiteimages/galleryimage/sofa22.jpg" alt="" class="gallery-item" data-color="" data-category="">
                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($results AS $works) {
                                    // Access the values of each row here
                                    // For example, you can access the "category" and "color" columns like this:
                                    $category = $works['category'];
                                    $color = $works['color'];
                                    $path = $works['img_path'];
                                    // Use the values to display the gallery items
                                    echo "
                                        <div class='gallery-item'>
                                            <img src='{$path}' class='gallery-image' data-color='{$color}' data-category='{$category}' data-id='{$works['works_id']}'>
                                            <div class='action-buttons'>
                                                <button class='edit-button'>Edit</button>
                                                <button class='delete-button'>Delete</button>
                                            </div>
                                        </div>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                    <img src="/websiteimages/Divider.png" div class="services_works_text_divider_img">
                    <div class="services_works_hero_image_2">
                        <div>
                            <h1>
                                Discover Our Artistry
                            </h1>
                            <a href="services_craftsmanship.php" class="btn btn-black">See More</a>
                        </div>
                    </div>
                </div>
                <div class="tab" data-page="order">
                    <div class="featured-banner">
                        <h1 class="order-page__title">Design, Craft, Quote - All in One Place</h2>
                    </div>
                    <div class="faq">
                        <h1 class="faq__title">Frequently Asked Questions</h1>
                        <ol class="faq__list">
                            <?php
                                $sql = "SELECT * FROM faqs";
                                $stmt = $conn->query($sql);
                                $faqs = $stmt->fetchAll();
                                foreach ($faqs as $faq) {
                                    echo "
                                        <div class='faq__item editable' data-faq-id='{$faq['faq_id']}'>
                                            <li class='faq__question'>{$faq['question']}</li>
                                            <p class='faq__answer'>{$faq['answer']}</p>
                                        </div>
                                    ";
                                }
                            ?>
                        </ol>
                    </div>
                    <div class="order-testimonials-cta">
                        <p>See What Our Clients Say</p>
                        <a href="testimonials.php">See More</a>
                    </div>
                </div>
                <div class="tab" data-page="testimonial">
                    <img src="/websiteimages/testimonialsfeaturedimg.png" alt="" class="testimonials-featured-img">
                    <div class="testimonials-title">
                        <h2 class="editable short-text" data-id="TESTIMONIALHEADERTITLE"><?= stripslashes(html_entity_decode($contentsById["TESTIMONIALHEADERTITLE"]["content_text"])) ?></h2>
                        <p class="editable long-text" data-id="TESTIMONIALHEADERSUBTEXT"><?= stripslashes(html_entity_decode($contentsById["TESTIMONIALHEADERSUBTEXT"]["content_text"])) ?></p>
                        <a href="order.php">Get a Free Quote</a>
                    </div>
                </div>
                <div class="tab" data-page="about">
                    <img src="/websiteimages/teampicture.png" alt="" class="about-us-featuredimg">
                    <div class="about-us-container">
                        <h2 class="editable short-text" data-id="ABOUTHEADERTITLE"><?= stripslashes(htmlspecialchars_decode($contentsById["ABOUTHEADERTITLE"]["content_text"])) ?></h2>
                        <p class="editable long-text" data-id="ABOUTHEADERTEXT"><?= stripslashes(htmlspecialchars_decode($contentsById["ABOUTHEADERTEXT"]["content_text"])) ?></p>
                        <a href="order.php" class="btn btn-black">Get a Free Quote</a>
                    </div>
                    <div class="about-us-history about-us-container">
                        <h2 class="text-gold short-text editable" data-id="ABOUTHISTORYTITLE"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTHISTORYTITLE"]["content_text"]))?></h2>
                        <p class="long-text editable" data-id="ABOUTHISTORYTEXT"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTHISTORYTEXT"]["content_text"]))?></p>
                    </div>
                    <div class="values-philosophy about-us-container">
                        <h2 class="long-text editable" data-id="ABOUTVALUESTITLE"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESTITLE"]["content_text"]))?></h2>
                        <div class="values-container">
                            <div class="values">
                                <img src="" alt="" class="values-logo">
                                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING1"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING1"]["content_text"]))?></h3>
                                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT1"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT1"]["content_text"]))?></p>
                            </div>
                            <div class="values">
                                <img src="" alt="" class="values-logo">
                                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING2"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING2"]["content_text"]))?></h3>
                                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT2"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT2"]["content_text"]))?></p>
                            </div>
                            <div class="values">
                                <img src="" alt="" class="values-logo">
                                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING3"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING3"]["content_text"]))?></h3>
                                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT3"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT3"]["content_text"]))?></p>
                            </div>
                            <div class="values">
                                <img src="" alt="" class="values-logo">
                                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING4"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING4"]["content_text"]))?></h3>
                                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT4"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT4"]["content_text"]))?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab" data-page="contact">
                    <img src="/websiteimages/callcenterdude.png" div class="call_center_dude">
                    <h1 class="contact_us_text_title">Get In Touch With Us</h1>
                    <div class="contact_us_text">
                        <div class="contact_info">
                            <h1> Cellphone Number </h1>
                            <p class="short-text editable" data-id="CPNNUM"><?= stripslashes(htmlspecialchars_decode($contentsById["CPNNUM"]["content_text"])) ?></p>
                            <h1> Mailing Adress </h1>
                            <p class="short-text editable" data-id="MAILADD"><?= stripslashes(htmlspecialchars_decode($contentsById["MAILADD"]["content_text"])) ?></p>
                        </div>
                        <div class="adresses">
                            <h1>Business Address</h1>
                            <p class="long-text editable" data-id="BUSIADD"><?= stripslashes(htmlspecialchars_decode($contentsById["BUSIADD"]["content_text"])) ?></p>
                            <h1>Business Hours</h1>
                            <p class="short-text editable" data-id="BUSIHRS"><?= stripslashes(htmlspecialchars_decode($contentsById["BUSIHRS"]["content_text"])) ?></p>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-background">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="edit-title">Edit Gallery Image</h2>
                    <span class="close-modal" onclick="closeModal()">&times;</span>
                </div>
                <form action="" method="post" name="add-gallery-image" class="edit-form" enctype="multipart/form-data" data-for="add-image">
                    <label for="category">Category</label><br>
                    <input type="text" id="category" name="category" class="input" required placeholder="Category"><br>
                    <br>
                    <label for="color">Color</label><br>
                    <input type="text" id="color" name="color" class="input" required placeholder="Color"><br><br>
                    <br>
                    <!-- File input for image upload -->
                    <label for="file">Image</label>
                    <input type="file" id="fileInput" name="file" accept="image/*" class="Images_button" required><br>
                    <div class="image-container"></div>
                    <img src="#" alt="" id="image-preview">
                    <!-- Preview container for uploaded images -->
                    <br>
                    <div class="button_container">
                        <button type="submit" class="button btn-save">Save</button>
                        <button type="button" class="button btn-cancel" onclick="cancelModal()">Cancel</button>
                    </div>
                </form>
                <form action="" method="post" name="edit-short-text" class="edit-form" data-for="edit-short-text">
                    <label for="content_text">Info</label><br>
                    <input type="text" id="" name="content_text" class="input" required placeholder="Info Here"><br>
                    <br>
                    <div class="button_container">
                        <button type="submit" class="button btn-save">Save</button>
                        <button type="button" class="button btn-cancel" onclick="cancelModal()">Cancel</button>
                    </div>
                </form>
                <form action="" method="post" name="edit-long-text" class="edit-form" data-for="edit-long-text">
                    <label for="content_text">Info</label><br>
                    <textarea type="text" id="" name="content_text" class="input long-text-editor" required placeholder="Info Here"></textarea><br>
                    <br>
                    <div class="button_container">
                        <button type="submit" class="button btn-save">Save</button>
                        <button type="button" class="button btn-cancel" onclick="cancelModal()">Cancel</button>
                    </div>
                </form>
                <form action="" method="post" name="edit-faq" class="input faq-editor" data-for="edit-faq">
                    <label for="faq-question">Question</label><br>
                    <input type="text" id="" name="faq-question" class="input" required placeholder="Info Here"><br>
                    <br>
                    <label for="faq-answer">Answer</label><br>
                    <textarea type="text" id="" name="faq-answer" class="input" required placeholder="Info Here"></textarea><br>
                    <br>
                    <div class="button_container">
                        <button type="submit" class="button btn-save">Save</button>
                        <button type="submit" class="button btn-delete">Delete</button>
                        <button type="button" class="button btn-cancel" onclick="cancelModal()">Cancel</button>
                    </div>
                </form>
                <form action="" method="post" name="edit-website-image" class="input" data-for="edit-website-image">
                    <label for="file">Image</label>
                    <input type="file" id="fileInput" name="file" accept="image/*" class="Images_button" required><br>
                    <div class="image-container"></div>
                    <img src="#" alt="" id="image-preview">
                    <div class="button_container">
                        <button type="submit" class="button btn-save">Save</button>
                        <button type="button" class="button btn-cancel" onclick="cancelModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <button class="back-to-top btn btn-black">Back To Top</button> 
    <script src="../js/cms.js"></script>
    <script src="../js/sidebar.js"></script>
</body>
</html>