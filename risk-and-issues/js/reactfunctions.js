const configLightbox = (props) => {
    const [state, setState] = React.useState({
      mode: { use: false },
      format: { type: "radio", options: ["grid", "accordion"] },
      pagesize: { type: "text" },
    });
  
    const handleInputChange = (event, key) => {
      const value = event.target.type === "checkbox" ? event.target.checked : event.target.value;
      setState({ ...state, [key]: { ...state[key], value } });
    };
  
    const renderInput = (key, config) => {
        if (config.type === "radio") {
            return React.createElement(
              "div",
              { key: key },
              React.createElement(
                "label",
                null,
                key + ":"
              ),
              config.options.map((option) =>
                React.createElement(
                  "label",
                  { key: option },
                  option,
                  React.createElement("input", {
                    type: "radio",
                    name: key,
                    value: option,
                    onChange: (event) => handleInputChange(event, key),
                  })
                )
              )
            );
        } else if (config.type === "checkbox") {
            return React.createElement(
            "div",
            { key: key },
            React.createElement(
                "label",
                null,
                key + ":",
                React.createElement("input", { type: "checkbox", onChange: (event) => handleInputChange(event, key) })
            )
            );
        } else if (config.type === "text") {
            return React.createElement(
            "div",
            { key: key },
            React.createElement(
                "label",    
                null,
                key + ":",
                React.createElement("input", { type: "text", onChange: (event) => handleInputChange(event, key) })
            )
            );
        }
      return null;
    };
  
    return React.createElement(
        "div",
        {
          className: "config-lightbox",
          onClick: (e) => e.stopPropagation(),
        },
        Object.entries(state).map(([key, config]) => renderInput(key, config))
      );
};

function renderLightbox() {
    console.log("in render")
    ReactDOM.render(React.createElement(configLightbox), document.getElementById("config-lightbox-container"));
    showLightbox();
}
  
function showLightbox() {
    console.log("showing config-lightbox");
    const container = document.getElementById("config-lightbox-container");
    container.style.display = "block";
    container.classList.add("config-lightbox-container");
    container.onclick = () => hideLightbox();
}
  
function hideLightbox() {
    console.log("hide")
    const container = document.getElementById("config-lightbox-container");
    container.style.display = "none";
    container.classList.remove("config-lightbox-container");
}
    
