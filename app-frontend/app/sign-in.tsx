import React, { useState } from "react";
import {
  View,
  Text,
  TextInput,
  StyleSheet,
  Alert,
  ActivityIndicator,
  TouchableOpacity,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { useRouter } from "expo-router"; // Use useRouter hook
import axios from "axios"; // Import axios

interface ErrorResponse {
  data: {
    message: string;
  };
}

const EXPO_PUBLIC_API_URL = process.env.EXPO_PUBLIC_API_URL;

export default function SignIn() {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const router = useRouter(); // Get router from useRouter hook

  const handleSignIn = async () => {
    if (!username || !password) {
      Alert.alert("Error", "Please fill all fields.");
      return;
    }

    setLoading(true);
    try {
      console.log("Sending request to:", `${EXPO_PUBLIC_API_URL}/login`);

      const response = await axios.post(
        `${EXPO_PUBLIC_API_URL}/login`,
        { username, password },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      console.log("Response Status:", response.status);

      const { user, token } = response.data[0];

      // Store user details and token in AsyncStorage
      await AsyncStorage.setItem("@user", JSON.stringify(user));
      await AsyncStorage.setItem("@token", token);

      // Navigate to home
      router.replace("/(app)"); // Use the replace method for navigation
    } catch (error: unknown) {
      if (error instanceof Error && "response" in error) {
        const err = error as unknown as ErrorResponse;
        const errorMessage =
          err.data?.message || "Login failed. Please try again.";

        Alert.alert("Error", errorMessage);
      } else {
        console.error("Unexpected error:", error);
        Alert.alert("Error", "An unexpected error occurred. Please try again.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Sign In</Text>
      <TextInput
        style={[styles.input, { textTransform: "lowercase" }]}
        placeholder="Username"
        value={username}
        onChangeText={setUsername}
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      <TouchableOpacity
        style={styles.button}
        onPress={handleSignIn}
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator size="large" color="#ffffff" />
        ) : (
          <Text style={styles.buttonText}>Sign In</Text>
        )}
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 16,
  },
  input: {
    width: "80%",
    height: 40,
    borderColor: "#ccc",
    borderWidth: 1,
    borderRadius: 4,
    marginBottom: 12,
    paddingHorizontal: 8,
  },
  button: {
    width: "80%",
    height: 40,
    backgroundColor: "#007bff",
    justifyContent: "center",
    alignItems: "center",
    borderRadius: 4,
  },
  buttonText: {
    color: "#ffffff",
    fontSize: 16,
  },
});
