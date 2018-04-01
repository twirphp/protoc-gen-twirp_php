package main

import (
	"bytes"
	"text/template"
	"github.com/Masterminds/sprig"
	"strings"
)

func TxtFuncMap() template.FuncMap {
	funcMap := sprig.TxtFuncMap()

	funcMap["eachTitle"] = eachFunc(strings.Title)

	funcMap["service_comment"] = ServiceComment
	funcMap["method_comment"] = MethodComment

	funcMap["php_namespace"] = PhpNamespace
	funcMap["php_fqn"] = PhpFQN
	funcMap["php_service_name"] = PhpServiceName

	return funcMap
}

// each applies a function to each string in a slice.
func each(s []string, fn func(string) string) []string {
	for key, value := range s {
		s[key] = fn(value)
	}

	return s
}

// eachFunc makes a simple string transformation function to an each function.
func eachFunc(fn func(string) string) func([]string) []string {
	return func(s []string) []string {
		return each(s, fn)
	}
}

// executeTemplate executes a template and returns the result.
func executeTemplate(t string, data interface{}) (string, error) {
	buf := new(bytes.Buffer)

	tpl, err := template.New("").Funcs(TxtFuncMap()).Parse(t)
	if err != nil {
		return "", err
	}

	err = tpl.Execute(buf, data)
	if err != nil {
		return "", err
	}

	return buf.String(), nil
}