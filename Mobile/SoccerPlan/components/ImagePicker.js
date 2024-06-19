import React, { useState } from 'react';
import { View, Button, Image, Alert } from 'react-native';
import axios from 'axios';

const ImageUploader = () => {
  const [image, setImage] = useState(null);

  const pickImage = async () => {
    // Code to pick an image from the device, you can use a library like react-native-image-picker

    // For simplicity, I'm using a static image URL for this example
    const imageUrl = 'https://i.pinimg.com/564x/fd/c3/90/fdc390219ceadedb3536a349b4996894.jpg';

    // Call the function to upload the image to the PHP API
    await uploadImage(imageUrl);
  };

  const uploadImage = async (imageUrl) => {
    try {
      // Convert the image URL to a blob
      const response = await fetch(imageUrl);
      const blob = await response.blob();

      // Create form data to send the blob to the server
      const formData = new FormData();
      formData.append('image', blob, 'image.jpg');

      // Make a POST request to your PHP API endpoint
      const apiEndpoint = 'https://your-api-endpoint.com/upload.php';
      const apiResponse = await axios.post(apiEndpoint, formData);

      // Handle the API response, you may want to check for success and show a message
      console.log('API Response:', apiResponse.data);

      // Update your React Native state or perform any other necessary actions
      // For example, you might store the image URL in your database after a successful upload

    } catch (error) {
      console.error('Error uploading image:', error);
      // Handle errors, show an alert, etc.
      Alert.alert('Error', 'Failed to upload image');
    }
  };

  return (
    <View>
      {image && <Image source={{ uri: image }} style={{ width: 200, height: 200 }} />}
      <Button title="Pick Image" onPress={pickImage} />
    </View>
  );
};

export default ImageUploader;