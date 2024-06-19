import { Text, StyleSheet, Image, View, Avatar, Dimensions, TouchableOpacity } from 'react-native';
import { Header, HeaderProps } from '@rneui/themed';
import Icon from 'react-native-vector-icons/Feather';
import { useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
// import { Bell } from 'lucide-react-native';
// import { Bell } from 'lucide-react-native';

import { useRefresh } from '../Context/RefreshContext';
import { useUser } from '../Context/UserContext';

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

export default function HeaderBar({userLogged, screen, onSettingsPressed, onNotificationsPressed, onRefreshPressed}) {
  // console.log("user: ", user);
  const [isSettingsPressed, setIsSettingsPressed] = useState(false);
  const [isNotificationsPressed, setIsNotificationsPressed] = useState(false);
  // const [isRefreshPressed, setIsRefreshPressed] = useState(false);
  const { isRefreshPressed, setRefreshPressed } = useRefresh();
  const { dataUser } = useUser();

  const handleSettingsPressed = () => {
    setIsSettingsPressed(!isSettingsPressed);

    // Call the function passed from Profile.js
    if (onSettingsPressed) {
      onSettingsPressed(!isSettingsPressed);
    }
  };

  const handleNotificationsPressed = () => {
    setIsNotificationsPressed(!isNotificationsPressed);

    // Call the function passed from Profile.js
    if (onNotificationsPressed) {
      onNotificationsPressed(!isNotificationsPressed);
    }
  };

  // const handleLogout = async () => {
  //     await AsyncStorage.removeItem('userData');

  //     setLoggedUser(null);
  //     setIsLogged(false);
  // }

  const getInitials = (name) => {
    const words = name.split(' ');
    return words.map(word => word.charAt(0)).join('').toUpperCase();
  };

  const handleRefreshPressed = () => {
    setRefreshPressed(!isRefreshPressed);

    if(!isRefreshPressed){
      setTimeout(() => {
        setRefreshPressed(false);
        // console.log('done');
      }, 2000);
    }

    if (onRefreshPressed) {
      onRefreshPressed(!isRefreshPressed);
    }
  }

  return(
    <Header backgroundColor='#041b2b'>
        <View style={styles.containerImg}>
        {/* <TouchableOpacity onPress={() => console.log("Image Pressed")}> */}
          {/* <Image style={styles.headerImg}
            source={require('../../assets/img/brasil.jpg')}
          /> */}
          <Text style={styles.headerImg}>{getInitials(dataUser.username)}</Text>
        {/* </TouchableOpacity> */}
        </View>
        <View style={styles.containerCenter}>
            <Text style={styles.headerCenter}>{screen}</Text>
        </View>
        {screen == 'Profile' ? (
          <>
            <View style={styles.containerIconsP}>
              {/* Settings */}
               <TouchableOpacity onPress={handleSettingsPressed} >
                <Icon style={[styles.IconSettings, {color: isSettingsPressed ? '#f50443' : 'white'}]} name="log-out" size={25} />
              </TouchableOpacity>

              {/* Refresh */}
              <TouchableOpacity onPress={handleRefreshPressed} disabled={isRefreshPressed}>
                <Icon style={[styles.IconRefresh, {color: isRefreshPressed ? '#f50443' : 'white'}]}  name="refresh-cw" size={25} color={'white'} />
              </TouchableOpacity>

              {/* <TouchableOpacity onPress={() => console.log("Tlimm")}>
                <Icon style={styles.IconNotifications} name="bell" size={25} color={'white'} />
              </TouchableOpacity> */}
            </View>
          </>
        ) : (
           <>
             <View style={styles.containerIconsP}>
               {/* Refresh */}
              <TouchableOpacity onPress={handleRefreshPressed} disabled={isRefreshPressed}>
                <Icon style={[styles.headerIcon, {color: isRefreshPressed ? '#f50443' : 'white'}]} name="refresh-cw" size={25} color={'white'} />
              </TouchableOpacity>
             </View>
           </>
         )}
    </Header>
    )
}

const styles = StyleSheet.create ({
  containerImg: {
    width: 70,
    justifyContent: 'center',
    height: Platform.OS == "ios" ? 35 : 45,
    width: Platform.OS == "ios" ? 35 : 45,
    borderRadius: Platform.OS == "ios" ? 25 : 30,
    // alignItems: 'center',
  },
  headerImg: {
    height: Platform.OS == "ios" ? 35 : 45,
    width: Platform.OS == "ios" ? 35 : 45,
    borderRadius: Platform.OS == "ios" ? 25 : 30,
    backgroundColor: 'white',
    alignItems: 'center'
  },
  containerCenter: {
    justifyContent: 'center',
    alignItems: 'center',
    // borderWidth: 1,
    width: 100,
    borderColor: 'pink',
    height: Platform.OS == "ios" ? 36 : 45,
  },
  headerCenter: {
    alignItems: 'center',
    textAlign: 'center',
    fontSize: 22,
    fontWeight: 'bold',
    color: 'white',
    width: 130,
  },
  containerIcon: {
    flexDirection: 'row',
    alignItems: 'center',
    width: 7,
    height: Platform.OS == "ios" ? 35 : 45,
    // borderColor: 'red',
    // borderWidth: 1,
  },
  headerIcon: {
    marginLeft: 45
  },
  containerIconsP: {
    flexDirection: 'row',
    alignItems: 'center',
    width: 70,
    height: Platform.OS == "ios" ? 35 : 45,
    // borderColor: 'red',
    // borderWidth: 1,
  },
  IconSettings: {
    marginLeft: 11,
  },
  IconRefresh: {
    marginLeft: 10
  },
  IconNotifications: {
    marginLeft: 7
  },
});