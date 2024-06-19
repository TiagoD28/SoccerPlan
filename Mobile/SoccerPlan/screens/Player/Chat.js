import React, { useEffect, useState, useCallback } from 'react';
import { StyleSheet, Text } from 'react-native';
import { GiftedChat } from 'react-native-gifted-chat';
import socket from '../../websocket-php/src/socket';
import { io } from 'socket.io-client';
import sendDataToApi from '../../getPostApi/sendDataToApi'; // Import your sendDataToApi utility

import HeaderBar from '../../components/HeaderBar/HeaderBar';
import Refresh from '../../components/HeaderBar/Refresh';
import { getMessages, sendMessage } from '../../websocket-php/src/getSendMessages';
import { useUser } from '../../components/Context/UserContext';
import { useRefresh } from '../../components/Context/RefreshContext';
// import { useMessage } from '../../components/Context/MessageContext';

const Chat = ({ userData }) => {
  const userLogged = userData;
  const [messages, setMessages] = useState([]);
  const { dataUser } = useUser();
  const { isRefreshPressed, setRefreshPressed } = useRefresh();
  // const { isMessageSended, setMessageSended } = useMessage();


  useEffect(() => {
    console.log('Chat');
    const fetchMessages = async () => {
      console.log('Called');
      try {
        // Call your API function to get messages
        const apiResponse = await getMessages(dataUser.idTeam);
        
        const apiMessages = apiResponse['data'];

        console.log('Api Messages',apiMessages);
        // Transform the API response into the format expected by GiftedChat

        if(apiMessages){
          const sortedMessages = apiMessages.sort((a, b) => {
            return new Date(b.timestamp) - new Date(a.timestamp);
          });

          const formattedMessages = sortedMessages.map(message => ({
            // _id: message.idSender, // Adjust this based on your API response
            _id: `${message.idMessage}_${message.username}`, // Use a unique combination
            text: message.content,
            createdAt: new Date(message.timestamp), 
            user: {
              _id: message.idSender, // Adjust this based on your API response
              name: message.username,
              avatar: message.img, // Adjust this based on your API response
            },
          }));

          // Update the state with the formatted messages
        setMessages(formattedMessages);
        }      

        // Update the state with the formatted messages
        // setMessages(formattedMessages);
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    };

    const handleReceiveMessage = (message) => {
      setMessages(previousMessages => GiftedChat.append(previousMessages, message));
    };

    fetchMessages();

    socket.on('receiveMessage', handleReceiveMessage);

    // setMessageSended(false);

    return () => {
      // Unsubscribe from the 'receiveMessage' event when the component unmounts
      socket.off('receiveMessage', handleReceiveMessage);
    };
  }, [isRefreshPressed]);

  useEffect(() => {
    // Establish a WebSocket connection when the component mounts
    socket.connect();

    // Clean up the connection when the component unmounts
    return () => {
      socket.disconnect();
    };
  }, []);

  const onSend = useCallback((messages = []) => {
    setMessages(previousMessages =>
      GiftedChat.append(previousMessages, messages),
    )
    const{
      _id,
      text,
      user
    }=messages[0]
      sendMessage(dataUser.idTeam, dataUser.idUser, text);

      // setMessageSended(true);
  }, []);

  const getAvatarInitials = (name) => {
    // Get the first letter of each word in the name
    const initials = name.split(' ').map(word => word.charAt(0)).join('');
  
    // Limit the initials to a certain length (e.g., 2)
    return initials.substring(0, 2).toUpperCase();
  };

  const handleRefreshPressed = () => {
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
    }, 2000);
  };

  return (
    <>
       <HeaderBar
            // userLogged={dataUser}
            screen={'Chat'}
            onRefreshPressed={handleRefreshPressed}
          />
          {userLogged.idTeam ? (
            <>
              {isRefreshPressed ? (
                <Refresh userLogged={dataUser} />
              ) : (
                // <>
                // {sortedMessages ? (
                  <GiftedChat
                  messages={messages}
                  showAvatarForEveryMessage={true}
                  onSend={messages => onSend(messages)}
                  user={{
                    _id: dataUser.idUser,
                    name: dataUser.username,
                    avatar: dataUser.img ? dataUser.img : getAvatarInitials(dataUser.username),
                  }}
                />
                // ) : (
                //   <Text>There is no Messages!</Text>
                // )}
                // </>
              )}
            </>
          ) : (
            <Text>You must be on a Team!</Text>
          )}
        </>

  );
};

export default Chat;




// import React, { useEffect, useState, useCallback } from 'react';
// import { StyleSheet, Text } from 'react-native';
// import { GiftedChat } from 'react-native-gifted-chat';
// import socket from '../../websocket-php/src/socket';
// import sendDataToApi from '../../getPostApi/sendDataToApi'; // Import your sendDataToApi utility

// import HeaderBar from '../../components/HeaderBar/HeaderBar';
// import Refresh from '../../components/HeaderBar/Refresh';
// import { getMessages, sendMessage } from '../../websocket-php/src/getSendMessages';
// import { useUser } from '../../components/Context/UserContext';

// const Chat = ({ userData }) => {
//   const userLogged = userData;
//   const [isRefreshing, setIsRefreshing] = useState(false);
//   const [messages, setMessages] = useState([]);
//   const { dataUser } = useUser();

//   useEffect(() => {
//     console.log('UseEffect');
//     const fetchMessages = async () => {
//       console.log('Called');
//       try {
//         // Call your API function to get messages
//         const apiResponse = await getMessages(dataUser.idTeam);
//         const apiMessages = apiResponse['data'];

//         console.log('Api Messages',apiMessages);
//         // Transform the API response into the format expected by GiftedChat

//         if(apiMessages){
//           const sortedMessages = apiMessages.sort((a, b) => {
//             return new Date(b.timestamp) - new Date(a.timestamp);
//           });

//           const formattedMessages = sortedMessages.map(message => ({
//             // _id: message.idSender, // Adjust this based on your API response
//             _id: `${message.idMessage}_${message.username}`, // Use a unique combination
//             text: message.content,
//             createdAt: new Date(message.timestamp), 
//             user: {
//               _id: message.idSender, // Adjust this based on your API response
//               name: message.username,
//               avatar: message.img, // Adjust this based on your API response
//             },
//           }));

//           // Update the state with the formatted messages
//         setMessages(formattedMessages);
//         }      

//         // Update the state with the formatted messages
//         // setMessages(formattedMessages);
//       } catch (error) {
//         console.error('Error fetching messages:', error);
//       }
//     };

//     const handleReceiveMessage = (message) => {
//       setMessages(previousMessages => GiftedChat.append(previousMessages, message));
//     };

//     fetchMessages();

//     socket.on('receiveMessage', handleReceiveMessage);

//     return () => {
//       // Unsubscribe from the 'receiveMessage' event when the component unmounts
//       socket.off('receiveMessage', handleReceiveMessage);
//     };
//   }, [dataUser.idTeam]);

//   const onSend = useCallback((messages = []) => {
//     setMessages(previousMessages =>
//       GiftedChat.append(previousMessages, messages),
//     )
//     const{
//       _id,
//       text,
//       user
//     }=messages[0]
//       sendMessage(dataUser.idTeam, dataUser.idUser, text);
//   }, []);

//   const getAvatarInitials = (name) => {
//     // Get the first letter of each word in the name
//     const initials = name.split(' ').map(word => word.charAt(0)).join('');
  
//     // Limit the initials to a certain length (e.g., 2)
//     return initials.substring(0, 2).toUpperCase();
//   };

//   const handleRefreshPressed = () => {
//     setIsRefreshing(true);
//     setTimeout(() => {
//       setIsRefreshing(false);
//     }, 2000);
//   };

//   return (
//     <>
//        <HeaderBar
//             // userLogged={dataUser}
//             screen={'Chat'}
//             onRefreshPressed={handleRefreshPressed}
//           />
//           {userLogged.idTeam ? (
//             <>
//               {isRefreshing ? (
//                 <Refresh userLogged={dataUser} />
//               ) : (
//                 // <>
//                 // {sortedMessages ? (
//                   <GiftedChat
//                   messages={messages}
//                   showAvatarForEveryMessage={true}
//                   onSend={messages => onSend(messages)}
//                   user={{
//                     _id: dataUser.idUser,
//                     name: dataUser.username,
//                     avatar: dataUser.img ? dataUser.img : getAvatarInitials(dataUser.username),
//                   }}
//                 />
//                 // ) : (
//                 //   <Text>There is no Messages!</Text>
//                 // )}
//                 // </>
//               )}
//             </>
//           ) : (
//             <Text>You must be on a Team!</Text>
//           )}
//         </>

//   );
// };

// export default Chat;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'white',
    alignItems: 'center',
    justifyContent: 'center',
  },

  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'black',
  },
});