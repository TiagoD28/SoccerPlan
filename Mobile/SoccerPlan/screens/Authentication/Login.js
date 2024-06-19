import React, {useState, useEffect} from 'react';
import { StyleSheet, View, Text, Image, Dimensions, TextInput, TouchableOpacity, Platform, TouchableHighlight} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import * as Application from 'expo-application';
// import * as Network from 'expo-network';
// import RadioButtons, { SegmentedControls } from 'react-native-radio-buttons';
// import useData from '../../getPostApi/useData';
import Config from '../../getPostApi/config';
import SegmentedControlComponent from '../../components/SegmentControlComponent';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import showToast from '../../components/Toast/Toast';
import { useUser } from '../../components/Context/UserContext';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Underline } from 'lucide-react-native';


export default function Login({ onLoginSuccess }){
// export default function Login(){
  const [user, setUser] = useState('');
  const [pass, setPass] = useState('');
  // const [deviceMac, setDeviceMac] = useState();
  // const [typeUser, setTypeUser] = useState('');
  const { setLoggedUser } = useUser();
  
  // const data = useData(Config.apiUrl+"Users?route=getUsers");

  const navigation = useNavigation();

  const onLoginPressed = () => {
    const loginAsync = async () => {
      // const dataToSend = {user, pass, typeUser};
      const dataToSend = {user, pass};
      const apiResponse = await sendDataToApi('Authentication', 'login', dataToSend);
      // const apiResponseData = apiResponse.data;

      if(apiResponse.status == '400'){
        showToast('error', 'Error', apiResponse.message);
        setUser('');
        setPass('');
      } else if(apiResponse.status == '200') {
        showToast('success', 'Success', apiResponse.message);

        console.log('Login: ',apiResponse.data);

        // await setUserDataInStorage(apiResponse.data);

        await AsyncStorage.setItem('userData', JSON.stringify(apiResponse.data));

        // setLoggedUser(apiResponse.data);
        if(apiResponse.data.idCoach){
          console.log('Coach');
          setLoggedUser({idCoach: apiResponse.data.idCoach, age: apiResponse.data.age, nacionality: apiResponse.data.nacionality, 
            img: apiResponse.data.img, idClub: apiResponse.data.idClub, phoneNumber: apiResponse.data.phoneNumber,
            idUser: apiResponse.data.idUser, idClothingSize: apiResponse.data.idClothingSize, typeUser: apiResponse.data.typeUser, 
            email: apiResponse.data.email, firstName: apiResponse.data.firstName, lastName: apiResponse.data.lastName, 
            username: apiResponse.data.username, idTeam: apiResponse.data.idTeam});
        } else if(apiResponse.data.idPlayer){
          console.log('Player');
          setLoggedUser({idPlayer: apiResponse.data.idPlayer, age: apiResponse.data.age, nacionality: apiResponse.data.nacionality, 
            weight: apiResponse.data.weight, imc: apiResponse.data.imc, phoneNumber: apiResponse.data.phoneNumber,
            img: apiResponse.data.img, idClub: apiResponse.data.idClub, 
            idUser: apiResponse.data.idUser, idClothingSize: apiResponse.data.idClothingSize, typeUser: apiResponse.data.typeUser, 
            email: apiResponse.data.email, firstName: apiResponse.data.firstName, lastName: apiResponse.data.lastName, 
            username: apiResponse.data.username, position: apiResponse.data.position, state: apiResponse.data.state, 
            idTeam: apiResponse.data.idTeam});
        }
        onLoginSuccess(apiResponse.data);
      }
    }
    loginAsync();
  }

  // const handleTypeSelected = (selectedSegment) => {
    // setTypeUser(selectedSegment);
  // }

  return(
    <View style={styles.container}>
      <Image style={styles.logo}
        source={require('../../assets/img/splash.png')}
      />
      <Text style={styles.title}>Login</Text>
        {/* <SegmentedControlComponent onTypeSelected={handleTypeSelected} options={['Admin', 'Coach', 'Player']} /> */}
      {/* <View style={styles.inputContainerUser}>  
        <Text style={styles.placeholderText}>#{typeUser || ''}</Text>
        <TextInput
          style={styles.inputUser}
          onChangeText={(text) => setUser(text)}
          value={user}
          placeholder="Username/Email"
          placeholderTextColor="black"
        />
      </View> */}
      
      <TextInput style={styles.input}
        onChangeText={(text) => setUser(text)}
        value={user}
        placeholder="Email/Username"
        placeholderTextColor="gray"
        keyboardType="email-address"
      />
        <TextInput 
          style={styles.input}
          secureTextEntry={true}
          onChangeText={(text) => setPass(text)}
          value={pass}
          placeholder="Password"
          placeholderTextColor="gray" 
        />
      {/* <TextInput
          style={styles.inputPass}
          secureTextEntry={true}
          onChangeText={(text) => setPass(text)}
          value={pass}
          placeholder="placeholder"
          placeholderTextColor="black"
        /> */}
        <View style={styles.buttonContainer}>
          <TouchableHighlight
            style={styles.loginButton}
            underlayColor="#f50443"
            onPress={() => onLoginPressed()}
          >
            <Text style={styles.buttonText}>Login</Text>
          </TouchableHighlight>
          <TouchableOpacity onPress={() => navigation.navigate('Register')}>
            <Text style={styles.registerText}>I don't have an account yet! Register!</Text>
          </TouchableOpacity>
        </View>
    </View>
    
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'white',
    alignItems: 'center',
  },
  logo: {
    marginTop: '15%',
    width: '40%',
    height: '20%'
  },
  input: {
    // height: '8%',
    width: windowWidth,
    borderBottomWidth: 1,
    // borderTopWidth: 1,
    borderColor: 'gray',
    // marginTop: '2%',
    marginLeft: '1%',
    width: '90%',
    paddingTop: '10%',
    paddingLeft: '5%',
    color: 'black'
  },
  title: {
    fontSize: 30,
    fontWeight: 'bold',
    color: 'black',
    padding: 15,
  },
  buttonContainer: {
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 20,
  },
  loginButton: {
    backgroundColor: '#041b2b',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
    marginBottom: 10,
  },
  buttonText: {
    color: 'white',
    // fontWeight: 'bold',
    fontSize: 16,
  },
  registerText: {
    color: '#f50443',
    fontSize: 16,
    textDecorationLine: 'underline'
  },


  // input: {
  //   height: 60,
  //   width: windowWidth,
  //   // borderWidth: 1,
  //   borderBottomWidth: 1,
  //   borderTopWidth: 1,
  //   borderColor: 'gray',
  //   marginTop: 10,
  //   padding: 15
  // },
  // container: {
  //   flex: 1,
  //   backgroundColor: 'white',
  //   alignItems: 'center',
  //   justifyContent: 'center',
  //   paddingHorizontal: 30
  // },
  // inputContainerUser: {
  //   flexDirection: 'row',
  //   alignItems: 'center',
  //   borderColor: 'gray',
  //   borderWidth: 1,
  //   padding: 10,
  // },
  // placeholderText: {
  //   color: '#f50443',
  //   textTransform: 'uppercase',
  // },
  // inputUser: {
  //   height: 40,
  // },
  // inputContainerPass: {
  //   flexDirection: 'row',
  //   alignItems: 'center',
  //   borderColor: 'gray',
  //   borderWidth: 1,
  //   padding: 10,
  // },
  // inputPass: {
  //   height: 40
  // },
  // title: {
  //   fontSize: 30,
  //   fontWeight: 'bold',
  //   color: 'black',
  //   padding: 15
  // }
});