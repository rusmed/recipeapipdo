В этой версии API используется docker. Фреймворк Lumen. Для работы с БД используется PDO. 

В первой версии API (https://github.com/rusmed/recipeapi) используются миграции. Для работы с БД используется встроенный Eloquent.

# Install with docker

    chmod 777 uploads    # Grant write permissions for /uploads path
    docker-compose up -d
    
    http://localhost:8000

# This manual describes the API commands for recipes

### Authentication

    User should signup and signin with credentials (email, password). User will recieve a token for next API calls.
    
    Likewise, you can use the 401 - Unauthorized status code to indicate that a user could not authenticate.
    
### HTTP Status Code Meaning

**Successful**

    - `200 OK` - Everything worked as expected.
    - `201 Created` - The request was successful and a resource was created. This is typically a response from a `POST` request to create a resource, such as the recipe or upload image.
    - `204 No Content` - The request was successful but the response body is empty. This is typically a response from a `DELETE` request to delete a resource.

**Error**

    - `400 Bad Request` - A required parameter or the request is invalid.
    - `401 Unauthorized` - The authentication credentials are invalid.
    - `404 Not Found` - The requested resource doesn’t exist.
    
### API methods

**Sign Up**

    POST /api/signup
    
    body params:
    - name
    - email
    - password
    
    responses:
        201: created user
        400: validation failed
        
    curl -X POST \
      http://localhost:8000/api/signup \
      -H 'cache-control: no-cache' \
      -H 'content-type: application/x-www-form-urlencoded' \
      -d 'name=<user_name>&email=<user_email>&password=<user_password>'
    
**Sign In**

    POST /api/signin
    
    body params
    - email
    - password
    
    responses:
        200 - contains token and timestamp of token expiration
        400 - validation failed
        401 - authentication failed
        
    curl -X POST \
      http://localhost:8000/api/signin \
      -H 'cache-control: no-cache' \
      -H 'content-type: application/x-www-form-urlencoded' \
      -d 'email=<user_email>&password=<user_password>'
      
**Upload image**

    POST /api/images
    
    body params
    - image
    
    responses:
        200 - uploaded image
        400 - validation failed
        401 - unauthorized
        
    curl -X POST \
      http://localhost:8000/api/images \
      -H 'api_token: <api_token>' \
      -H 'cache-control: no-cache' \
      -H 'content-type: multipart/form-data' \
      -F image=@<file_image>
        
**Add a recipe**

    POST /api/recipes
    
    body params
    - title
    - body
    - image_id
    
    responses:
        201 - created recipe
        400 - validation failed
        401 - unauthorized
        
    curl -X POST \
      http://localhost:8000/api/recipes \
      -H 'api_token: <api_token>' \
      -H 'cache-control: no-cache' \
      -H 'content-type: application/x-www-form-urlencoded' \
      -d 'title=<recipe_title>&body=<recipe_body>&image_id=<recipe_image_id>'
        
**Update recipe**

    PUT /api/recipes/{id}
    
    body params
    - title
    - body
    - image_id
    
    responses:
        200 - updated recipe
        400 - validation failed
        401 - unauthorized
        403 - forbidden
        
    curl -X PUT \
      http://localhost:8000/api/recipes/<recipe_id> \
      -H 'api_token: <api_token>' \
      -H 'cache-control: no-cache' \
      -H 'content-type: application/x-www-form-urlencoded' \
      -d 'title=<recipe_title>&body=<recipe_body>&image_id=<recipe_image_id>'
        
**Delete recipe**

    DELETE /api/recipes/{id}
    
    responses:
        204 - recipe has been deleted
        400 - object does not exist
        401 - unauthorized
        403 - forbidden
        
    curl -X DELETE \
      http://localhost:8000/api/recipes/<recipe_id> \
      -H 'api_token: <api_token>' \
      -H 'cache-control: no-cache'
      
**Get one recipe**

    GET /api/recipes/{id}
      
    responses:
        200 - ok
        404 - recipe not found
      
    curl -X GET \
      http://localhost:8000/api/recipes/<recipe_id> \
      -H 'cache-control: no-cache'
            
**Get all recipes**

    GET /api/recipes
      
    responses:
        200 - ok
      
    curl -X GET \
      http://localhost:8000/api/recipes \
      -H 'cache-control: no-cache'