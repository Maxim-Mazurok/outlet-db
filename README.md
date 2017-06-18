#### Editions

##### Add New One 
    GET /api
        ?table=editions
        &type=add
        &name=Edition 1
  
##### Get All
    GET /api
        ?table=editions
        &type=get

#### Edition Menu

##### Add New One
    POST /api
        ?table=edition_menu
        &type=add
        
    Request Payload
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="edition_name"
    
    edition_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="model_number"
    
    2
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="model_name"
    
    model_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="short_name"
    
    short_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="video_button"; filename="file_to_upload_1.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="subscription_button"; filename="file_to_upload_2.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="image_button"; filename="file_to_upload_3.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF--
    
#### Get All (Except images)
    GET /api
        ?table=edition_menu
        &type=get
    
#### Get Image
    GET /api
        ?table=edition_menu
        &type=get
        &model_number=2
        &edition_name=edition_name_1
        &file=image_button

#### Images Menu

##### Add New One
    POST /api
        ?table=images_menu
        &type=add
        
    Request Payload
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="edition_name"
    
    edition_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="model_number"
    
    2
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="model_name"
    
    model_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="short_name"
    
    short_name_1
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="video_button"; filename="file_to_upload_1.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="subscription_button"; filename="file_to_upload_2.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF
    Content-Disposition: form-data; name="image_button"; filename="file_to_upload_3.png"
    Content-Type: image/png
    
    
    ------WebKitFormBoundarykAdOVRwnUGoIy2QF--
    
#### Get All (Except images)
    GET /api
        ?table=images_menu
        &type=get
    
#### Get Image
    GET /api
        ?table=images_menu
        &type=get
        &edition_name=edition_name_1
        &model_number=2
        &product_id=123
        &file=image_button
