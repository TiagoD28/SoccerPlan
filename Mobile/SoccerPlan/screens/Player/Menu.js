import React, { useState, useEffect, useRef } from 'react';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';
import { StyleSheet, View, Text, Button, FlatList, SafeAreaView, TouchableOpacity, Dimensions } from 'react-native';

import useData from '../../getPostApi/useData';
import HeaderBar from '../../components/HeaderBar/HeaderBar';
import Refresh from '../../components/HeaderBar/Refresh';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import { useUser } from '../../components/Context/UserContext';
import { useRefresh } from '../../components/Context/RefreshContext';
import Team from './Team';

export default function Menu({userData}){
  const userLogged = userData;
  const { dataUser } = useUser();
  const { isRefreshPressed, setRefreshPressed } = useRefresh();
  const [ notifications, setNotifications ] = useState();
  const [ hasClub, setHasClub ] = useState();
  // const apiUrl = 'http://localhost/api/index.php';
  
  // const data = useData("http://192.168.0.22:3000/api/data");

  useEffect(() => {
    if(!hasClub){
      setHasClub(dataUser.idClub);
    }
    const getNotifications = async () => {
        const apiResponse = await sendDataToApi('Notifications', 'getNotifications', {"idClub": dataUser.idClub});
        console.log('Status: ',apiResponse.status);
        if(apiResponse.status == '200'){
          console.log('Notifications: ',apiResponse.data);
          setNotifications(apiResponse.data);
        } else {
          console.log('Message: ', apiResponse.message);
        }
    }
    getNotifications();
  }, [dataUser, isRefreshPressed]);

  const handleRefreshPressed = () => {
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
    }, 2000);
  }

  const renderNotificationItem = ({ item }) => {
    let title;
    let icon;

    switch (item.typeNotification) {
      case 'joinClub':
        icon = 'user-plus';
        title = 'Request';
        break;
      case 'joinTeam':
        icon = 'user-plus';
        title = 'Request';
        break;
      case 'joinClubAccepted':
        title = 'Request Accepted';
        icon = 'user-check';
        break;
      case 'joinTeamAccepted':
        title = 'Request Accepted';
        icon = 'user-check';
        break;
      case 'joinClubRejected':
        title = 'Request Rejected';
        icon = 'user-x';
        break;
      case 'joinTeamRejected':
        title = 'Request Rejected';
        icon = 'user-x';
        break;
      case 'userJoinedClub':
        title = 'New Member';
        icon = 'user-check';
        break;
      case 'userJoinedTeam':
        title = 'New Member';
        icon = 'user-check';
        break;
      case 'eventAdded':
        title = 'Event';
        icon = 'calendar';
        break;
      default:
        icon = 'help-circle';
    }

    // let modifiedDescription = item.descriptionN;
    // let clubName = '';

    // if (modifiedDescription.includes('Welcome') && dataUser.idUser != item.idExecuter) {
    //   console.log('Hi');
    //   const match = modifiedDescription.match('/Welcome to (.+)$/');
    //   // The regex captures the text after "Welcome to" until the end of the string
    //   clubName = match ? match[1].trim() : ''; // Extract the matched text and trim any extra spaces
    //   item.descriptionN = ''; // Set the original description to an empty string
    //   console.log(clubName);
    // }

    
    
    // Calculate time difference
    const timeExecuted = new Date(item.timeExecuted);
    const currentTime = new Date();
    const timeDifference = currentTime - timeExecuted;
    const hoursDifference = Math.floor(timeDifference / (1000 * 60 * 60));
    
    if((item.idClub != dataUser.idClub) ||  // case user its not from the same club
      (item.idTeam != null && dataUser.idTeam != item.idTeam) || // case notification its from a team and user its not in the same team
      (item.descriptionN.includes('entered') && item.idExecuter == dataUser.idUser) ||
      (item.descriptionN.includes('Welcome') && item.idExecuter != dataUser.idUser) ||
      (item.typeNotification == 'joinClubRejected' && item.idExecuter != dataUser.idUser) ||
      (item.typeNotification == 'joinTeamRejected' && item.idExecuter != dataUser.idUser)
    ){
    } else {
      console.log("Description: ", item.descriptionN , " idExecuter: ", item.idExecuter);
      return (
        <View style={styles.notfContainer}>
        {/* Section: Icon on the left */}
        <View style={styles.iconContainer}>
          <Feather name={icon} color={hoursDifference < 24 ? '#f50443' : 'white'} size={25} style={styles.icon} />
        </View>
        {/* Section: Description in the middle */}
        <View style={styles.descriptionContainer}>
          <Text style={styles.title}>{title}</Text>
          <Text style={styles.description}>{item.descriptionN}</Text>
        </View>
        {/* Section: Time in the right top */}
        <View>
          <Text style={styles.timeExecuted}>
            {hoursDifference < 24
              ? `${hoursDifference} hours ago`
              : timeExecuted.toDateString()}
          </Text>
        </View>
      </View>
      );  
    }
  };

  return (
    <>
      <HeaderBar userLogged={dataUser} screen={'Notifications'} onRefreshPressed={handleRefreshPressed} />
      {isRefreshPressed ? (
        <Refresh userLogged={dataUser} />
      ) : hasClub ? (
        <SafeAreaView style={styles.container}>
          <FlatList
            horizontal={false}
            data={notifications}
            keyExtractor={(notification) => `${notification.idNotification}`}
            contentContainerStyle={styles.flatListContainer}
            renderItem={renderNotificationItem}
          />
        </SafeAreaView>
      ) : (
        <View style={styles.container}>
          <Text>You have to be in a Club!</Text>
        </View>
      )}
    </>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'white',
    alignItems: 'center',
    justifyContent: 'center',
  },
  flatListContainer: {
    flexDirection: 'column',
    width: windowWidth,
  },
  notfContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    borderBottomWidth: 1,
    borderColor: 'gray',
    padding: 10,
  },
  iconContainer: {
    width: 70,
    height: 70,
    backgroundColor: '#041b2b',
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 10,
  },
  icon: {
    // marginRight: 8,
  },
  descriptionContainer: {
    flex: 1,
    marginHorizontal: 10
  },
  title: {
    fontSize: 15,
    fontWeight: 'bold',
  },
  // timeContainer: {
  //   // height: '100%'
  // },
  timeExecuted: {
    fontSize: 13,
    color: 'black',
    textAlign: 'right',
    fontWeight: 'bold'
  },
});