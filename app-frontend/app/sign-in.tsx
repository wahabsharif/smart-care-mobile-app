import React, { useState } from "react";
import { router } from "expo-router";
import { View, Text, TextInput, Button, StyleSheet } from "react-native";
import { useSession } from "@/components/auth/ctx";

export default function SignIn() {
  const { signIn } = useSession();
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  const handleSignIn = () => {
    // Dummy credentials
    const dummyUsername = "user";
    const dummyPassword = "1122";

    if (username === dummyUsername && password === dummyPassword) {
      // Store session data
      const token = "dummyToken123";
      signIn(); // Remove the arguments

      // Navigate to home
      router.replace("/");
    } else {
      alert("Invalid credentials");
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Sign In</Text>
      <TextInput
        style={styles.input}
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
      <Button title="Sign In" onPress={handleSignIn} />
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
    textTransform: "lowercase",
    borderColor: "#ccc",
    borderWidth: 1,
    borderRadius: 4,
    marginBottom: 12,
    paddingHorizontal: 8,
  },
});
