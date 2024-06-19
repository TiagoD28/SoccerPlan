import axios from 'axios';
import Config from './config';

const DEFAULT_INTERVAL = 10000;

async function teste(folder, route, data, callback, interval = DEFAULT_INTERVAL) {
  try {
    const baseUrl = Config.apiUrl + folder + '/index.php?route=';

    const sendRequest = async () => {
      try {
        const response = await axios.post(`${baseUrl}${route}`, JSON.stringify(data), {
          headers: {
            'Content-Type': 'application/json',
          },
        });

        // Call the callback with the response data
        // console.log("asdfasdf",response.data.data);
        callback(response.data.data);
      } catch (error) {
        console.error('Error sending data to API:', error.message);
      }
    };

    // Initial request
    sendRequest();

    // Periodic request
    const intervalId = setInterval(sendRequest, interval);

    // Cleanup interval on component unmount or as needed
    const cleanup = () => clearInterval(intervalId);

    // Return cleanup function
    return cleanup;

  } catch (error) {
    console.error('Error setting up periodic request:', error.message);
  }
}

export default teste;

// import axios from 'axios';
// import Config from './config';

// const DEFAULT_INTERVAL = 10000;

// async function teste(folder, route, data, interval = DEFAULT_INTERVAL) {
//   try {
//     const baseUrl = Config.apiUrl + folder + '/index.php?route=';
//     console.log('Data to send to API: ', data);

//     const sendRequest = async () => {
//       try {
//         const response = await axios.post(`${baseUrl}${route}`, JSON.stringify(data), {
//           headers: {
//             'Content-Type': 'application/json',
//             // Adjust other headers as needed
//           },
//         });

//         console.log('Response: ', response.data);
//         // Process the response or update state as needed
//       } catch (error) {
//         console.error('Error sending data to API:', error.message);
//       }
//     };

//     // Initial request
//     sendRequest();

//     // Periodic request
//     const intervalId = setInterval(sendRequest, interval);

//     // Cleanup interval on component unmount or as needed
//     const cleanup = () => clearInterval(intervalId);

//     // Return cleanup function
//     return cleanup;

//   } catch (error) {
//     console.error('Error setting up periodic request:', error.message);
//   }
// }

// export default teste;