# Magento 2 AI Review Validation

This cutting-edge extension, infused with the power of Artificial Intelligence, facilitates automatic validation of product reviews, ensuring that feedback is genuine, relevant, and free from spam or malicious content. By harnessing AI's deep learning capabilities, the extension streamlines the review moderation process.

## Features

- Swift Moderation. Significantly streamlines review moderation time. Say goodbye to long ours of reading reviews.
- Better Product Page Content. Propel your sales with information that matters to your customers.
- AI-Powered Review Checks. Seamlessly sync with the OpenAI moderation service to sift out reviews that don't align with your brand.
- Comprehensive Review Analysis. Easily check if a review contains sexual, hate, harassment, self-harm, threatening or violent language or expressions.
- Efficient Review Management. View the results of OpenAI moderation service in the Review Grid and Review Edit pages to make quick decisions before approving or rejecting reviews manually.
- Auto-Moderation Option. Let the AI take charge! Automate review statuses based on OpenAI's moderation verdicts.

# Instalation

Run:
```
composer require bydn/openai-review-validator
./bin/magento module:enable Bydn_OpenaiReviewValidator
./bin/magento setup:upgrade
```

# Dependencies

This extension needs an OpenAI API account and the corresponding API key.

To obtain your API key, go to https://platform.openai.com/ and sign in into your account or create a new one.

Then, go to your profile menu and select View API keys (please note than this menu can change over time):

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/1-openai-account.png" />

Create a new API by clicking the button “Create new secret key” and give it a name when asked.

Important: Copy your keys and save it in a secure place as you will not be able to see your key again after.

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/2-opena-api-keyi.png" />

IMPORTANT: As of the release of this guide, OpenAI is offering the moderation API for free. This means you won't incur any charges from OpenAI for using it. However, this could change in the future. Stay updated by checking OpenAI's pricing page at: https://openai.com/pricing

# Configuration

Access the configuration of the base extension going to Stores => Configuration => AI Extensions (by DN). Enable de API usage and paste the API you get on the previous section:

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/3-configuration.png" />

Once you have enabled the OpenAI integration and set your API Key, go to Open AI Review Validator (again inside Stores => Configuration => AI Extensions (by DN)) to configure your Review Validation.

## General Section

First, in the General section, configure the behavior of the extension:

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/4-configuration.png" />

**OpenAI Review Validator Enabled**. Turn this option on to start validating reviews against the OpenAI moderation API.

If this is enabled, the extension will validate Pending Reviews every 15 minutes against OpenAI API, storing the results in the Magento database and showing them in the Reviews section in the backoffice. See below.

**Auto validate reviews**. Enabling this option, makes the extension to automatically move Pending Reviews into Approval or Rejection status after OpenAI validation.

If this option is turned off, the extension will still validate reviews against OpenAI but the Review will remain in Pending Status until you check the result from OpenAI and change its status manually.
Remember that you can always manually change the status of any review, regardless of the automatic validation.

## Abusive Language Check Section

In this section you can enable and configure language analysis.

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/5-configuration.png" />

The lower is the threshold score for each category, the softer is the validation. 

For example, if you configure 0 for the Sexual Language threshold, that means the strongest validation will be done and no Sexual Language will be allowed at all.

If instead you set 100 for a category, that means that category will not be moderated and any related word or expression that fits that category will be allowed.

We recommend starting with the preconfigured levels and then fine-tune the thresholds for your particular business.

## Spam Check Section

In this section you can enable Spam analysis

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/6-configuration.png" />

The threshold acts the same way that explained before. 0 means strongest validation, so less spam expected to be validated. 100 means spam detection would be almost disabled. Recommended starting value is 75.

## Unrelated Content Check Section

In this section you can enable an additional validation to know if the review is related to the product being reviewed or at least it seems a product review.

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/7-configuration.png" />

First configuration text is the template that is sent to OpenAI API to ask about the likeness of the text to a product review. You can use a placeholder to include your product name, so the check is more related to the product.

If your products have not a meaningful name (including for example the type of product, like t-shirt, swatch, and so on), better remove the name of the product and make the validation to be generic, as generic names may confuse the AI.

# Where to see the collected data

Once you configure the extension, give some time to the extension to start validating pending reviews. Validation takes place every 15 minutes.

Then go to Marketing Reviews => All Reviews and you will start seeing the validation results.

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/8-configuration.png" />

Go into any review to see the validation details:

<img src="https://github.com/danidnm/ByDN-Magento-Chatgpt-Review-Validator/blob/master/docs/9-configuration.png" />

# Having problems

Contact me at soy (at) solodani.com

