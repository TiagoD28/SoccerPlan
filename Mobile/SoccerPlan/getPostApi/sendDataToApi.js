import axios from 'axios';
import Config from './config';

import socket from '../websocket-php/src/socket';

// const DEFAULT_INTERVAL = 10000;

async function sendDataToApi(folder, route, data) {
  try {
    const baseUrl = Config.apiUrl+folder+'/index.php?route=';
    // const baseUrl = 'http://192.168.34.136/api/index.php?route=';

    // inserir dados da data para listData e passar para o body

    // console.log(`${baseUrl}${route}`, JSON.stringify(data))
    console.log("Data to send to api: ",data);
    
    const response = await axios.post(`${baseUrl}${route}`, JSON.stringify(data), {
      headers: {
        'Content-Type': 'application/json', // Adjust the content type based on your API requirements
        // You might need additional headers, such as authentication headers
      }
    });

    // if(route == "sendMessage"){
    //   socket.emit('chat message', data);
    // }

    // console.log("Response: ", response.data);
    return response.data;

  } catch (error) {
    console.error('Error sending data to API:', error.message);
  }
}

export default sendDataToApi;