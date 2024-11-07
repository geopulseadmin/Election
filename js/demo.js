// URL of the JSON file or API
const url = 'candidate_data.json';

// Fetch data from the JSON file
fetch(url)
  .then(response => {
    // Check if the response is successful
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    // Parse the response as JSON
    return response.json();
  })
  .then(data => {
    // Handle the JSON data here
    console.log(data);
    // Example: Access a specific property
    console.log(data.someProperty,"data");
  })
  .catch(error => {
    // Handle any errors
    console.error('Error fetching JSON:', error);
  });