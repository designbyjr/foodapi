## API Challenge
The language I am most familar with is PHP. I have chossen Laravel as a framework because it has extra features for handling files.
It also has unit testing built in with the framework which is useful for testing RESTful API endpoints.

I have also opted for Laravel because if the resource where to expand to interact with other microservices it would be easy to implement.

I have opted for Get, Post, Put and Delete to explictly state what I am deleting. This also follows CRUD, 
to ensure routes match methods making sure my code is maintainable.
I have also ensured that I have complied with the HTTPS-only standard to ensure data remains encrypted on transit.

If i had the option of caching i would have used redis or even a database, however since this task is based on a file,
i have opted to use collections. This will ensure i can retrive from a collection when i need to.

Collections also have the advantage to be modified but also easily accessable ensuring that if future changes where to happen the code would not be redundent.

Mobile devices such as iOS and Andriod handle JSON Objects differently, that is why i have created an order for READ responses, 
ensuring the device can present data in the correct way. I also have made the time in milliseconds with unixtimestamps as they are universal.

## Installation
You will require the following prerequisites:

PHP 5.6+ installed Globally on your machine. If not installed go to the PHP <a href="http://php.net/manual/en/install.php">website</a>.

You will also need the latest version of Composer. If you are unsure if you have composer installed,
open Terminal (Linix) or the Command line (Windows) and type in composer and hit return. 

If a menu does not appear you will have to install it from this <a href="https://getcomposer.org/doc/00-intro.md">Link</a>.

Before you go any further make sure both PHP and composer run. This can be done with "composer.phar" or "composer" or "php composer".
PHP can be tested by running "php -i"; Menus should appear for both php and composer.

Once PHP and Composer are installed run the command below in the directory you want to run the code in:

Download the repository from `https://github.com/designbyjr/foodapi`

Now change the directory your in to foodapi and find the .env file, make sure settings are correct.

Then run the following code:

```
Composer install
```

## How It Works

The following are crud methods which have been tested in <a href="https://www.getpostman.com/apps">Postman</a> with a JSON body payload, the routes can be also tested with CURL and PHP Unit.

demo.demo is an alias for your specified name that you wish to use the application, normally it will be localhost:8000.

**Create**

The following options are optional and will be stored as null if not provided:
bulletpoint1,
bulletpoint2,
bulletpoint3,
short_title,
in_your_box

The following Curl example shows the typical body content to be sent in a Post action.


`curl -d`

```json
{
    "0": {
			"id":1,
			"created_at": 1435683480000,
			"updated_at": 1435683480000,
			"recipe_cuisine": "asian",
			"box_type": "vegetarian",
			"gousto_reference": 59,
			"season": "all",
				"in_your_box": null,
				"recipe_diet_type_id": "meat",
				"base": "noodles"
				"title": "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
				"slug": "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
				"short_title": null,
				"marketing_description": "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
				"bulletpoint1": null,
				"bulletpoint2": null,
				"bulletpoint3": null,
				"preparation_time_minutes": 35,
				"shelf_life_days": 4,
				"equipment_needed": "Appetite",
				"origin_country": "Great Britain",
				"calories_kcal": 401,
				"protein_grams": 12,
				"fat_grams": 35,
				"carbs_grams": 0,
				"protein_source": "beef"
			
		}
}
```

`-H "Content-Type: application/json" -X POST https://demo.demo/api/`

**Create Response**

The response from create should be a 200 and the following json response. 


**Read**

The Curl below is requesting one object.

`curl -d
-H "Content-Type: application/json" -X **GET**  https://demo.demo/api/?id[]=1`

The Curl below is requesting more than one object.

`curl -d
-H "Content-Type: application/json" -X **GET**  https://demo.demo/api/?id[]=1,2`

**Read Response**

The Response should be like this with a 200 response. The time has been converted to milliseconds so that mobile and web devices can handle date time. This currently shows one recipe as an example.

```json
{
	"response": 200,
	"links":{
		"self": "https://demo.demo/api/"
	},
	"data": [{
		"0": {
			"id":1,
			"created_at": 1435683480000,
			"updated_at": 1435683480000,
			"recipe_cuisine": "asian",
			"box_type": "vegetarian",
			"gousto_reference": 59,
			"season": "all",
			"ingredients": {
				"in_your_box": null,
				"recipe_diet_type_id": "meat",
				"base": "noodles"
			},
			"content": {
				"title": "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
				"slug": "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
				"short_title": null,
				"marketing_description": "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
				"bulletpoint1": null,
				"bulletpoint2": null,
				"bulletpoint3": null,
				"preparation_time_minutes": 35,
				"shelf_life_days": 4,
				"equipment_needed": "Appetite",
				"origin_country": "Great Britain"
			},
			"nutrition": {
				"calories_kcal": 401,
				"protein_grams": 12,
				"fat_grams": 35,
				"carbs_grams": 0,
				"protein_source": "beef"
			}
		}
	}]
}
```

The request below requests all recepies.

`curl -H "Content-Type: application/json" -X **GET**  https://demo.demo/api/cuisine`


The request below is for cuisine(s) which allow you to mutli-select cuisines and also specify the limit the page will return. The Maximum limit is 5. Cuisines are sorted by id.

`curl -d`
```json
{
    "cuisine": ["asian","italian"],
    "limit":3
}
```
`-H "Content-Type: application/json" -X **GET**  https://demo.demo/api/cuisine`

**Read Response Cuisine**

The Response should have pagination links, any links that cannot be generated will return null. To paginate you can use the links provided or by changing the curl request with a different number at the end. A 404 Not Found will return if a page is not available.

```json
{
	"response": 200,
	"page":1,
	"links": {
			"self": "https://demo.demo/api/cuisine",
			    "first": "https://demo.demo/api/cuisine/1",
				"last": "https://demo.demo/api/cuisine/5",
				"prev": null,
				"next": "https://demo.demo/api/cuisine/2"
	},
	"data": [{
		"0": {
			"id":1,
			"created_at": 1435683480000,
			"updated_at": 1435683480000,
			"recipe_cuisine": "asian",
			"box_type": "vegetarian",
			"gousto_reference": 59,
			"season": "all",
			"ingredients": {
				"in_your_box": null,
				"recipe_diet_type_id": "meat",
				"base": "noodles"
			},
			"content": {
				"title": "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
				"slug": "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
				"short_title": null,
				"marketing_description": "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
				"bulletpoint1": null,
				"bulletpoint2": null,
				"bulletpoint3": null,
				"preparation_time_minutes": 35,
				"shelf_life_days": 4,
				"equipment_needed": "Appetite",
				"origin_country": "Great Britain"
			},
			"nutrition": {
				"calories_kcal": 401,
				"protein_grams": 12,
				"fat_grams": 35,
				"carbs_grams": 0,
				"protein_source": "beef"
			}
		}
	}]
}
```



**Update**

This will update request **does not allow a mutli-select option with an array**, properties should be contained inside the modify object, if a modify object is incomplete or ill-formed or empty a 500 will be returned. If an ID cannot be found it will be a 404.


`curl -d`
```json
{
    "id":2,
    "modify":{
    	"protein_source": "beef"
    }
}
```
`-H "Content-Type: application/json" -X **PUT** https://demo.demo/api`

This will return the modified version of the recipe that was to be updated.


## Testing

Testing was done by testing for both web and api, with die and dump.

## Future Changes

In the future delete could be implemented and a better way to store information such as redis with keys.

Here is how the Delete request could look like:

**Delete**

The delete request below is for receipes which allow you to mutli-select recipes by ID. This will return a 200 if successful. A 404 will be returned if the ID is not found. A 500 will be for a method failure.

`curl -d`
```json
{
    "id": [1]
}
```
`-H "Content-Type: application/json" -X **DELETE** https://demo.demo/api`
