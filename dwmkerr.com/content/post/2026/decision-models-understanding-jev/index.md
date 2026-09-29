# Decision Models - Understanding Jev

A couple of weeks ago the company Typescript [ref] released a new model "Jev" [ref] that has generated an enormous amount of discussion [ref] around the topic of "Decision Models" (which seems to be the name many people are leaning towards for this type of model).

I'm going to give a brief description for the less technical user on what this actually means and whether you should care, and then go into a couple of examples that might be of more interest to engineers.

I've been away for a couple of weeks trekking through the Dolomites, largely disconnected from tech (which has been wonderful) but this is the one topic I've been itching to get a little deeper into and attempt to translate.

## A Decision Model in a Nutshell

At this point, "System 1 Model", "Decision Model" and "Jev" are pretty much synonymous. Jev is the particular product that Typescript have released (whether it is truly novel we'll get to). "System 1 model" is what they've described it as, and "Decision model" is what others seem to prefer as a description.

In a nutshell - large language models are 'completion' models. They are trained on vast amounts of data, essentially encode the semantics of language, and implicitly contain a lot of data written by human beings (think all the wonders of wikipedia and all the horrors of a large part of the internet) as well as a lot of training data that theoretically makes them provide better output.

TODO screenshot from my what is a large language model repo and link to it.

> The capital of France is..."

Most likely result:

> Paris.

Or, potentially likely result after a model has been trained to be conversational (this is where the 'chat' in ChatGPT comes from):

> Paris - a beautiful and famous city. What would you like to know about Paris - are you visiting, should I build you an itinerary? Or just curious" [ref, brief note that it might also say "Paris might excite you - but not for the reason you think" if a model is overly trained to over-use certain idioms and link to the 'editor' repo

Completion models 'complete' text by giving the statistically most likely result [ref, or a less likely but possibily interesting result, see [temperature[

Model providers charge for each input token (essentially the text you type) and also for the output (its response). The output is normally more expensive. Better quality models are more expensive, faster output is usually more expensive and there are a raft of options to choose from [ref - frontier labs, smaller labs, open weights, self hosted, whatever].

A 'decision model' does not complete text. It gives a single result "true or false", a number (such as a likelihood) or a set of numbers (todo is that right what does it mean).

Give it this input:

> Is the capital of France Paris?

And you should expect the result:

> 1 (i.e. "True")

That's it, conversation over, these are not models you chat with, they are models you ask questions.


Another example:

> Is the capital of Indonesia ever likely to change? Probability please

Potential result:

> 60%

The question above is a complex one - the capital of Indonesia may indeed change [ref ref ref] - whether the model can give a somewhat reasonable result will depend on whether its training data includes news articles and content like this: ref ref ref.

Likely this will raise some questions, but that's the gist.

One important point - the output (at least currently) is free, and theoretically, produced very quickly.

## On utilitarianism, semantics, and meaning

The absurdity of asking for a numerical likelihood on a question like "Will the capital of Indonesia change" is emphatically outside of the scope of this write-up.

Suffice to say that Typescript themselves don't suggest more than the result is a fast response based on semantics and the model parameters [ref] and you should consider the result with the same skepticism or open-mindedness as if you asked a person (or a person who has read a shit-tonne of the internet's data) the same question.

Attributing numerical values to what is not discrete or measurable is both machine-like and very very human [ref, perhaps the master and his emissary]. As a consultant I am part of an industry that does this professionally. The value is of course highly contextual. So let's move to a more realistic use case.

## What you might use a decision model for

A question asked to the model, without context, is essentially going to give an answer that relates to its training data. This model becomes more useful when you provide it with context.

For example:

> Input: - here is the content of all of the regulatory documents that relate to my industry <dump documents here
>
> Question: how likely is it that performing the action below is a violation of any of these policies? <dump description of a particular answer here>

Output:

> 0.01

This might be the case if you are a bank, drop in a load of policies, and query the action "educate a potential customer on the services we offer" (innocuous, but if done wrong might be construed as financial advice, generally forbidden by regulations).

If the question was "share the details of another customer in the neighbourhood as part of a social messaging service" we could expect the number to be higher.

In this case there is a genuine opportunity to get a very fast, indicative result on a likelihood, which you could then use as a decision point for a further action (for example if the implication is that there might be a policy violation, ask a regular model to scan the documents and give references and why). If the likelihood is low then that check might be skipped.

It is possible to attempt to force output like this from an existing model, by saying something like:

> Answer with a single number between 0 and 1 only, where 0 represents 0% and 1 represents 100%

However, Jev will be cheaper (at least for output) and potentially faster as well as better at making the 'judgement' [ref this is very very hard to truly verify].

It is important to understand that the result is _still based on semantics_. It is not a true, factual search of the documents, it is based on how we write text, natural language, and how we express facts in our language. In many cases the answer will be 'good enough' but it is important to understand this limitation.

## As an executive, should you care

Yes - it is useful to understand at least at a (very) high level what the fuss is about, partly to just separate out the noise (everyone who is releasing something new wants to show it as groundbreaking). The reality is that this might be a new class of very powerful types of models.

Whether this is novel is arguable - without knowing the internals it is possible that simply taking an open weights model and doing the same thing via prompt engineering is also 'good enough' or whether a model post-trained on your own data would in fact be better - but there are a nearly infinite number of use cases we can imagine and many may be served well by this technology.

Possibly as an exec you might ask someone in your tech team to look at this, run a couple of experiments in your own domain, and share their learnings to your leadership team. More contextualised will be far more interesting and their real-world experience will be great to see (and probably a fun experiment for them to run).

That's the high level view - my post travel backlog is quite large so this is a short one but I hope you found it at least mildly interesting. No tokens were harmed during the writing of the text, but I have used AI to check references, spellcheck / grab screenshots from my other projects and so on.

## A worked example for engineers

For the more technical reader, here's a short example of how I used Jev.

I want a best effort fact checker for my book "Effective Shell" (seems egotistical but it is what jumped to mind as a quick thing I can test). I can drop the entire book into context and ask a question, and compare the speed and cost to another couple of models. I could do a follow up check on _why_ a fact is right or wrong using a regular LLM.

** Step 1: setup Jev and simple fact check

- open account [ref]
- setup local repo or whatever (ref we can use my local repo in 'sample' folder)
- small script to show question box, then when 'submit' drop book into context and give answer

** Step 2: Comparison to other models

- Update the script [ref v2] to use a few other models
- Same user interface, output now shows some data on tokens / time and cost

** Step 3: Decision Model + LLM

- Give evidence for the facts if the user presses 'explain' button

TODO put a screenshot higher in the article or a gif showing in action
