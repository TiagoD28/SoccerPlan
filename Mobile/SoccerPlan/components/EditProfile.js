import React, { useState, useEffect } from 'react';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';
import { TouchableOpacity, TextInput, View, Text, StyleSheet, Dimensions, Image } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import sendDataToApi from '../getPostApi/sendDataToApi';
import showToast from './Toast/Toast';

import { useUser } from './Context/UserContext';

export default function EditProfile() {
  const { dataUser } = useUser();
  const [formData, setFormData] = useState({
    idUser: dataUser.idUser,
    typeUser: dataUser.typeUser,
    firstName: dataUser.firstName || '',  
    lastName: dataUser.lastName || '',
    age: dataUser.age || '',
    email: dataUser.email || '',
    username: dataUser.username || '',
    nacionality: dataUser.nacionality || '',
    phoneNumber: dataUser.phoneNumber || '',
  });

  // useEffect(() => {
  //   (async () => {
  //     const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
  //     if (status !== 'granted') {
  //       console.error('Permission to access media library was denied!');
  //     }
  //   })();
  // }, []);

  // const handleImageSelection = async () => {
  //   console.log('image');
  //   const result = await ImagePicker.launchImageLibraryAsync({
  //     mediaTypes: ImagePicker.MediaTypeOptions.Images,
  //     allowsEditing: true,
  //     aspect: [4, 3],
  //     quality: 1,
  //   });

  //   if (!result.canceled) {
  //     const selectedImage = result.assets[0];
  //     const base64Image = await convertImageToBase64(selectedImage.uri);
  //     setFormData({
  //       ...formData,
  //       imageUri: selectedImage.uri,
  //       base64Image: base64Image
  //     });
  //   }

  //   // console.log('imageL: ',formData.base64Image);
  // };

  // const convertImageToBase64 = async (uri) => {
  //   const response = await fetch(uri);
  //   const blob = await response.blob();
  //   const reader = new FileReader();
  //   return new Promise((resolve, reject) => {
  //     reader.onload = () => resolve(reader.result.split(',')[1]);
  //     reader.onerror = reject;
  //     reader.readAsDataURL(blob);
  //   });
  // };
  

  const handleInputChange = (name, value) => {
    setFormData({
      ...formData,
      [name]: value,
    });
  };


  const handleSubmit = async () => {
    console.log('Submited');
    const profileData = {
      ...formData,
      // Other form data properties
    };

    const apiResponse = await sendDataToApi('Users', 'updateUser', profileData);

    if(apiResponse.status == "200"){
      showToast('success', 'Success', apiResponse.message);
      console.log('Image: ', apiResponse.data.img);
    } else {
      showToast('error', 'Error', apiResponse.message);
      console.log(apiResponse.message);
    }
  };

  // console.log('Image: ', dataUser.img, 'Image');

  return (
    <View style={styles.mainView}>
      <View style={styles.formContainer}>
        {/* <TouchableOpacity onPress={handleImageSelection} style={styles.imageButton}>
          <Image source={{ uri: formData.imageUri }} style={styles.selectedImage} />
          <Feather name="camera" size={24} color="black" style={styles.cameraIcon} />
        </TouchableOpacity> */}
        {/* Add other form inputs */}
        <Text style={styles.titleForm}>First Name</Text>
        <TextInput
          style={styles.input}
          placeholder="First Name"
          value={formData.firstName}
          onChangeText={(text) => handleInputChange('firstName', text)}
        />

        <Text style={styles.titleForm}>Last Name</Text>
        <TextInput
          style={styles.input}
          placeholder="Last Name"
          value={formData.lastName}
          onChangeText={(text) => handleInputChange('lastName', text)}
        />

        <Text style={styles.titleForm}>Username</Text>
        <TextInput
          style={styles.input}
          placeholder="Username"
          value={formData.username}
          onChangeText={(text) => handleInputChange('username', text)}
        />

        <Text style={styles.titleForm}>Email</Text>
        <TextInput
          style={styles.input}
          placeholder="Email"
          value={formData.email}
          onChangeText={(text) => handleInputChange('email', text)}
        />

        <Text style={styles.titleForm}>Phone Number</Text>
        <TextInput
          style={styles.input}
          placeholder="Phone Number"
          value={formData.phoneNumber}
          onChangeText={(text) => handleInputChange('phoneNumber', text)}
        />

        <Text style={styles.titleForm}>Age</Text>
        <TextInput
          style={styles.input}
          placeholder="Age"
          value={formData.age}
          onChangeText={(text) => handleInputChange('age', text)}
        />

        <Text style={styles.titleForm}>Nationality</Text>
        <TextInput
          style={styles.input}
          placeholder="Nationality"
          value={formData.nacionality}
          onChangeText={(text) => handleInputChange('nacionality', text)}
        />
        
        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit}>
          <Text style={styles.submitButtonText}>Save</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  header: {
    backgroundColor: '#041b2b',
    width: windowWidth,
    flexDirection: 'row',
    padding: 10
  },
  title: {
    fontSize: 15,
    color: 'white',
    marginLeft: 10, 
  },
  icon: {
    marginTop: 10
  },
   //Modal View
  formContainer: {
    flex: 1,
    // alignItems: 'center',
    width: windowWidth
  },
  titleModal: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'white',
    marginLeft: 40,
    marginRight: 45,
    marginTop: 85,
  },
  bottomModalView: {
    width: windowWidth,
    height: windowHeight,
    backgroundColor: 'white',
    shadowColor: '#000',
    padding: 10,
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  titleForm: {
    marginLeft: 15,
    fontSize: 20,
    fontWeight: 'bold',
  },
  button: {
    borderRadius: 20,
    padding: 10,
    elevation: 2,
  },
  textStyle: {
    color: 'white',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center',
  },
  input: {
    height: 40,
    // width: '100%',
    margin: 12,
    borderWidth: 1,
    borderRadius: 10,
    padding: 10,
},
});