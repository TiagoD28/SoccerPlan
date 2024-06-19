import React, {useState} from 'react';
import { StyleSheet, View, Text, Button, Image, useWindowDimensions, Linking, TouchableOpacity, TextInput, Dimensions, TouchableHighlight} from 'react-native';
import { useNavigation } from '@react-navigation/native';

import useData from '../../getPostApi/useData';
import Config from '../../getPostApi/config';
import SegmentedControlComponent from '../../components/SegmentControlComponent';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import showToast from '../../components/Toast/Toast';

export default function Register(){
  // const [selectedType, setSelectedType] = useState(null);
  const [typeUser, setTypeUser] = useState('');
  const [email, setEmail] = useState('');
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  // const dataToSend = [{typeUser, email, password, username}];

  const navigation = useNavigation();

  const onRegisterPressed = () => {
    const registerAsync = async () => {
      const userData = { typeUser, email, firstName, lastName, password, username};
        const apiResponse = await sendDataToApi('Authentication', 'register', userData);
        console.log(apiResponse.message);
        switch(apiResponse.status){
          case '400-1':
            showToast('error', 'Error', apiResponse.message);
            setTypeUser('');
            break;
          case '400-2':
            showToast('error', 'Error', apiResponse.message);
            setEmail('');
            break;
          case '400-3':
            showToast('error', 'Error', apiResponse.message);
            setFirstName('');
            break;
          case '400-4':
            showToast('error', 'Error', apiResponse.message);
            setLastName('');
            break;
          case '400-5':
            showToast('error', 'Error', apiResponse.message);
            setPassword('');
            break;
          case '400-6':
            showToast('error', 'Error', apiResponse.message);
            setUsername('');
            break;
          default:
            showToast('success', 'Success', apiResponse.message);
            navigation.navigate('Login');
        }  
      }
      registerAsync();
    };

  const handleTypeSelected = (selectedSegment) => {
    setTypeUser(selectedSegment);
  }

  return(
    <View style={styles.container}>
      <View style={styles.containerCenter}>
        <Image style={styles.logo}
          source={require('../../assets/img/splash.png')}
        />
        <Text style={styles.title}>Register</Text>
      </View>
        <SegmentedControlComponent onTypeSelected={handleTypeSelected} options={['Coach', 'Player']} />
        <TextInput style={styles.input}
          onChangeText={(text) => setEmail(text)}
          value={email}
          placeholder="Email"
          placeholderTextColor="gray" 
        />
        <TextInput style={styles.input}
          onChangeText={(text) => setFirstName(text)}
          value={firstName}
          placeholder="First Name"
          placeholderTextColor="gray" 
        />
        <TextInput style={styles.input}
          onChangeText={(text) => setLastName(text)}
          value={lastName}
          placeholder="Last Name"
          placeholderTextColor="gray" 
        />
        <TextInput 
          style={styles.input}
          secureTextEntry={true}
          onChangeText={(text) => setPassword(text)}
          value={password}
          placeholder="Password"
          placeholderTextColor="gray" 
        />
        <TextInput style={styles.input}
        onChangeText={(text) => setUsername(text)}
        value={username}
        placeholder="Username"
        placeholderTextColor="gray" />
        <View style={styles.buttonContainer}>
          <TouchableHighlight
            style={styles.registerButton}
            underlayColor="#f50443" // Set the color when the button is pressed
            onPress={(() => onRegisterPressed())}
          >
            <Text style={styles.buttonText}>Register</Text>
          </TouchableHighlight>
          <TouchableOpacity onPress={() => navigation.navigate('Login')}>
            <Text style={styles.loginText}>I already have an account!</Text>
          </TouchableOpacity>
        </View>
      </View>
    // </View>
  );
}
const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'white'
  },
  containerCenter: {
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: '-10%',
    marginBottom: '-32%'
  },
  logo: {
    marginBottom: '-2%',
    width: '42%',
    height: '36%'
  },
  input: {
    // height: '8%',
    width: windowWidth,
    borderBottomWidth: 1,
    // borderTopWidth: 1,
    borderColor: 'gray',
    // marginTop: '2%',
    marginLeft: '5%',
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
  registerButton: {
    backgroundColor: '#041b2b',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
    marginBottom: 10,
  },
  buttonText: {
    color: 'white',
    fontSize: 16,
  },
  loginText: {
    color: '#f50443',
    fontSize: 16,
    textDecorationLine: 'underline'
  },
});